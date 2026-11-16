<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index()
    {
        $user = Auth::user();
        $certificates = $user->certificates()->latest()->paginate(10);
        
        // Agregar estadísticas a la colección para la vista
        $certificates->totalHours = $user->certificates()->sum('total_hours');
        $certificates->activeCount = $user->certificates()->where('status', 'active')->count();
        
        return view('certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new certificate (Admin only).
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $users = User::where('role', '!=', 'admin')->get();
        return view('certificates.create', compact('users'));
    }

    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_hours' => 'required|integer|min:1',
            'issue_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        $certificate = Certificate::create([
            'user_id' => $request->user_id,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'total_hours' => $request->total_hours,
            'issue_date' => $request->issue_date,
            'description' => $request->description,
            'status' => 'active',
        ]);

        // Crear notificación de certificado generado
        NotificationService::certificateGenerated($request->user_id, $certificate->certificate_number);

        return redirect()->route('certificates.index')->with('success', 'Certificate created successfully.');
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        // Check if user can view this certificate
        if (Auth::user()->id !== $certificate->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Generate and download PDF certificate.
     */
    public function download(Certificate $certificate)
    {
        // Check if user can view this certificate
        if (Auth::user()->id !== $certificate->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'));
        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(Certificate $certificate)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $users = User::where('role', '!=', 'admin')->get();
        return view('certificates.edit', compact('certificate', 'users'));
    }

    /**
     * Update the specified certificate in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_title' => 'required|string|max:255',
            'hours_volunteered' => 'required|integer|min:1',
            'issue_date' => 'required|date',
            'status' => 'required|in:active,revoked',
        ]);

        $data = $request->only(['user_id', 'activity_title', 'hours_volunteered', 'issue_date', 'status']);
        $data['total_hours'] = $request->hours_volunteered;
        
        $certificate->update($data);

        return redirect()->route('admin.certificates')->with('success', 'Certificado actualizado exitosamente.');
    }

    /**
     * Remove the specified certificate from storage.
     */
    public function destroy(Certificate $certificate)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        $certificate->delete();

        return redirect()->route('certificates.index')->with('success', 'Certificate deleted successfully.');
    }

    /**
     * Validate certificate by number (public route).
     */
    public function validateCertificate(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|string',
        ]);

        $certificate = Certificate::where('certificate_number', $request->certificate_number)->first();

        if (!$certificate) {
            return view('certificates.validate', [
                'certificate' => null,
                'message' => 'Certificate not found.',
            ]);
        }

        return view('certificates.validate', compact('certificate'));
    }

    /**
     * Show validation form.
     */
    public function showValidateForm()
    {
        return view('certificates.validate-form');
    }

    /**
     * Get completed activities for a user.
     */
    public function getUserCompletedActivities($userId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($userId);
        $activities = $user->participatingActivities()->get()->map(function($activity) {
            $hours = 0;
            if ($activity->start_date && $activity->end_date) {
                $hours = $activity->start_date->diffInHours($activity->end_date);
            }
            
            return [
                'id' => $activity->id,
                'name' => $activity->name,
                'hours' => $hours,
                'dates' => $activity->start_date ? $activity->start_date->format('d/m/Y') . ' - ' . ($activity->end_date ? $activity->end_date->format('d/m/Y') : '') : 'Sin fecha'
            ];
        });

        return response()->json($activities);
    }
}
