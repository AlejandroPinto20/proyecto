<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mostrar solo las donaciones del usuario actual
        $donations = Auth::user()->donations()
            ->with(['verifiedBy'])
            ->latest()
            ->paginate(10);
        
        // Verificar si puede hacer donaciones
        $canMakeDonation = Auth::user()->canMakeDonation();
        $pendingDonation = Auth::user()->getPendingDonation();
            
        return view('donations.index', compact('donations', 'canMakeDonation', 'pendingDonation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo verificar para usuarios no administradores
        if (!Auth::user()->isAdmin()) {
            $hasPendingDonations = Auth::user()->donations()
                ->whereIn('validation_status', ['pending', 'verifying'])
                ->exists();
                
            if ($hasPendingDonations) {
                return redirect()->route('donations.index')
                    ->with('error', 'No puedes crear una nueva donación mientras tengas donaciones pendientes de verificación.');
            }
        }
        
        return view('donations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Solo verificar para usuarios no administradores
        if (!Auth::user()->isAdmin()) {
            $hasPendingDonations = Auth::user()->donations()
                ->whereIn('validation_status', ['pending', 'verifying'])
                ->exists();
                
            if ($hasPendingDonations) {
                return redirect()->route('donations.index')
                    ->with('error', 'No puedes crear una nueva donación mientras tengas donaciones pendientes de verificación.');
            }
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1|max:99999999.99',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:qr_code,bank_transfer',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => 'money',
            'status' => 'pending',
            'validation_status' => 'pending',
            'donation_date' => $request->donation_date,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
        ];

        // Generate QR code data for QR payment method
        if ($request->payment_method === 'qr_code') {
            $qrData = [
                'amount' => $request->amount,
                'description' => $request->title,
                'recipient' => 'Fundación UNIFRANZ',
                'account' => '1234567890',
                'bank' => 'Banco Unión',
                'reference' => 'DON-' . time() . '-' . Auth::id(),
            ];
            $data['qr_code_data'] = json_encode($qrData);
        }

        // For bank transfer, set bank account info
        if ($request->payment_method === 'bank_transfer') {
            $data['bank_account_info'] = 'Banco Unión - Cuenta Corriente: 1234567890 - Beneficiario: Fundación UNIFRANZ - NIT: 123456789';
        }

        $donation = Auth::user()->donations()->create($data);
        
        // Generar código de verificación
        $donation->generateVerificationCode();

        return redirect()->route('donations.index')->with('success', 'Donación registrada exitosamente. Código de verificación: ' . $donation->verification_code);
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        $this->authorize('view', $donation);
        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        $this->authorize('update', $donation);
        return view('donations.edit', compact('donation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        $this->authorize('update', $donation);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0|max:99999999.99',
            'type' => 'required|in:money',
            'status' => 'required|in:pending,approved,completed,cancelled',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:qr_code,bank_transfer',
            'bank_account_info' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        $data['type'] = 'money'; // Force type to money only

        // Generate QR code data for QR payment method
        if ($request->payment_method === 'qr_code') {
            $qrData = [
                'amount' => $request->amount,
                'description' => $request->title,
                'recipient' => 'Fundación UniFránz',
                'reference' => 'DON-' . time() . '-' . Auth::id(),
            ];
            $data['qr_code_data'] = json_encode($qrData);
        }

        // For bank transfer, set bank account info
        if ($request->payment_method === 'bank_transfer') {
            $data['bank_account_info'] = 'Cuenta Corriente: 1234567890 - Banco Unión - Fundación UniFránz';
        }

        $donation->update($data);

        return redirect()->route('donations.index')->with('success', 'Donación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);
        $donation->delete();

        return redirect()->route('donations.index')->with('success', 'Donación eliminada exitosamente.');
    }
}
