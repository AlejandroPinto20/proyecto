<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use App\Models\Certificate;
use App\Models\Donation;
use App\Models\Ranking;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{


    /**
     * Dashboard administrativo principal
     */
    public function dashboard()
    {
        // Estadísticas generales
        $stats = [
            'total_users' => User::count(),
            'total_activities' => Activity::count(),
            'total_certificates' => Certificate::count(),
            'total_donations' => Donation::count(),
            'total_donations_amount' => Donation::where('validation_status', 'approved')->sum('amount'),
            'cash_payments' => Donation::where('payment_method', 'cash')->where('status', 'completed')->count(),
            'qr_payments' => Donation::where('payment_method', 'qr_code')->where('status', 'completed')->count(),
            'bank_transfers' => Donation::where('payment_method', 'bank_transfer')->where('status', 'completed')->count(),
            'online_payments' => Donation::where('payment_method', 'online_payment')->where('status', 'completed')->count(),
            'active_activities' => Activity::where('status', 'active')->count(),
            'pending_activities' => Activity::where('status', 'pending')->count(),
            'pending_participations' => DB::table('activity_user')->where('status', 'pending')->count(),
        ];

        // Usuarios recientes (últimos 7 días)
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(5)
            ->get();

        // Actividades recientes
        $recentActivities = Activity::with('user')->latest()->take(5)->get();

        // Top voluntarios
        $topVolunteers = Ranking::with('user')
            ->orderBy('total_points', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentActivities', 'topVolunteers'));
    }

    /**
     * Gestión de usuarios
     */
    public function users()
    {
        $users = User::with(['activities', 'certificates', 'donations', 'ranking'])
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'ends_with:@est.unifranz.edu.bo,@docente.unifranz.edu.bo,@unifranz.edu.bo'],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,voluntario,estudiante',
            'phone' => 'required|string|regex:/^[0-9]{8}$/',
            'cod_est' => 'required|string|max:20|unique:users',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Use cod_est as password if no password provided
        $password = $request->password ?: $request->cod_est;

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'phone' => $request->phone,
            'cod_est' => $request->cod_est,
            'bio' => $request->bio,
            'points' => 0,
            'password_changed' => $request->password ? true : false,
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,voluntario,estudiante',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'points' => 'required|integer|min:0',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'role', 'phone', 'bio', 'points']);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo_path && \Storage::disk('public')->exists($user->profile_photo_path)) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new profile photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function toggleUserStatus(User $user)
    {
        // Prevent deactivating the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
            return redirect()->route('admin.users')->with('error', 'No puedes desactivar el último administrador activo.');
        }

        // Prevent an admin from deactivating another admin
        if ($user->isAdmin() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.users')->with('error', 'No puedes desactivar a otro administrador.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.users')->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Gestión de actividades
     */
    public function activities()
    {
        $activities = Activity::with('user')->paginate(15);
        return view('admin.activities.index', compact('activities'));
    }

    public function approveActivity(Activity $activity)
    {
        $activity->update(['status' => 'active']);

        // Send notification to all users when activity is approved
        $users = User::where('role', '!=', 'admin')->get();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\NewActivityNotification($activity));
        }

        return redirect()->back()->with('success', 'Actividad aprobada.');
    }

    public function rejectActivity(Activity $activity)
    {
        $activity->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Actividad rechazada.');
    }

    public function completeActivity(Activity $activity)
    {
        $activity->update(['status' => 'completada']);
        
        // Obtener participantes con status 'joined'
        $participants = $activity->users()->wherePivot('status', 'joined')->get();
        
        // Marcar como completados y actualizar habilidades
        foreach ($participants as $user) {
            $activity->users()->updateExistingPivot($user->id, ['status' => 'completed']);
            $user->updateSkillExperienceFromActivity($activity);
        }
        
        return redirect()->back()->with('success', 'Actividad completada, participantes actualizados y experiencia de habilidades otorgada.');
    }

    public function cancelActivity(Activity $activity)
    {
        $activity->update(['status' => 'cancelada']);
        return redirect()->back()->with('success', 'Actividad cancelada.');
    }

    public function editActivity(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function updateActivity(Request $request, Activity $activity)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,pending,completed,cancelled',
        ]);

        $activity->update($request->all());
        return redirect()->route('admin.activities')->with('success', 'Actividad actualizada.');
    }

    /**
     * Estadísticas y reportes
     */
    public function reports()
    {
        // Estadísticas mensuales de actividades
        $monthlyActivityStats = DB::table('activities')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        // Estadísticas mensuales de donaciones
        $monthlyDonationStats = DB::table('donations')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total_amount, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->where('validation_status', 'approved')
            ->groupBy('month', 'year')
            ->orderBy('month')
            ->get();

        // Estadísticas por rol
        $roleStats = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();

        // Top actividades
        $topActivities = Activity::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(10)
            ->get();

        // Estadísticas de donaciones
        $donationStats = [
            'total_donations' => Donation::count(),
            'total_donation_amount' => Donation::where('validation_status', 'approved')->sum('amount'),
            'pending_donations' => Donation::where('validation_status', 'pending')->count(),
            'approved_donations' => Donation::where('validation_status', 'approved')->count(),
            'rejected_donations' => Donation::where('validation_status', 'rejected')->count(),
            'completed_donations' => 0,
        ];

        // Top donadores
        $topDonors = User::withSum(['donations' => function ($query) {
            $query->where('validation_status', 'approved');
        }], 'amount')
            ->orderBy('donations_sum_amount', 'desc')
            ->take(10)
            ->get();

        // Estadísticas de certificados
        $certificateStats = [
            'total_certificates' => Certificate::count(),
            'total_hours' => Certificate::sum('hours_volunteered'),
        ];

        // Estadísticas adicionales
        $totalActivities = Activity::count();
        $activeActivities = Activity::whereIn('status', ['abierta', 'active', 'proxima'])->count();
        $totalParticipations = DB::table('activity_user')->count();
        $pendingParticipations = DB::table('activity_user')->where('status', 'pending')->count();
        $newUsersThisMonth = User::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $averageHours = $totalUsers > 0 ? $certificateStats['total_hours'] / $totalUsers : 0;
        
        // Crecimiento de usuarios por mes
        $userGrowthStats = DB::table('users')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->where('role', '!=', 'admin')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        // Actividades por categoría
        $activitiesByCategory = Activity::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return view('admin.reports', compact(
            'monthlyActivityStats',
            'monthlyDonationStats',
            'roleStats',
            'topActivities',
            'donationStats',
            'topDonors',
            'certificateStats',
            'totalActivities',
            'activeActivities',
            'totalParticipations',
            'pendingParticipations',
            'newUsersThisMonth',
            'averageHours',
            'userGrowthStats',
            'activitiesByCategory'
        ));
    }

    /**
     * Gestión de certificados
     */
    public function certificates()
    {
        $certificates = Certificate::with('user')->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function createCertificate()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.certificates.create', compact('users'));
    }

    public function storeCertificate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_title' => 'required|string|max:255',
            'hours_volunteered' => 'required|integer|min:1',
            'issue_date' => 'required|date',
        ]);

        $data = $request->only(['user_id', 'activity_title', 'hours_volunteered', 'issue_date']);
        $data['certificate_number'] = Certificate::generateCertificateNumber();
        $data['total_hours'] = $request->hours_volunteered;
        $data['status'] = 'active';

        $certificate = Certificate::create($data);

        $certificate->user->notify(new \App\Notifications\CertificateIssuedNotification($certificate));

        return redirect()->route('admin.certificates')->with('success', 'Certificado creado exitosamente.');
    }

    public function storeBulkCertificates(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'issue_date' => 'required|date',
        ]);

        $activity = Activity::findOrFail($request->activity_id);
        $participants = $activity->users()->whereIn('activity_user.status', ['joined', 'completed'])->get();
        
        $hours = 0;
        if ($activity->start_date && $activity->end_date) {
            $hours = $activity->start_date->diffInHours($activity->end_date);
        }

        $count = 0;
        foreach ($participants as $user) {
            Certificate::create([
                'user_id' => $user->id,
                'activity_title' => $activity->name,
                'hours_volunteered' => $hours,
                'total_hours' => $hours,
                'issue_date' => $request->issue_date,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'status' => 'active',
            ]);
            
            $count++;
        }

        return redirect()->route('admin.certificates')->with('success', "Se crearon {$count} certificados exitosamente.");
    }

    /**
     * Gestión de donaciones
     */
    public function donations()
    {
        $donations = Donation::with(['user', 'verifiedBy'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.donations.index', compact('donations'));
    }

    public function approveDonation(Donation $donation)
    {
        $donation->approve(auth()->user(), 'Donación verificada y aprobada por administrador');

        // Send notification to the donor
        $donation->user->notify(new \App\Notifications\DonationApprovedNotification($donation));

        return redirect()->back()->with('success', 'Donación aprobada exitosamente. El usuario ha ganado ' . $donation->calculatePoints() . ' puntos.');
    }

    public function rejectDonation(Request $request, Donation $donation)
    {
        $reason = $request->input('rejection_reason', 'Donación rechazada por el administrador');
        $donation->reject(auth()->user(), $reason);
        
        return redirect()->back()->with('success', 'Donación rechazada.');
    }

    /**
     * Configuración del sistema
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Ver logs de notificaciones
     */
    public function notificationLogs()
    {
        // Obtener todas las notificaciones de la base de datos
        $notifications = DB::table('notifications')
            ->join('users', 'notifications.notifiable_id', '=', 'users.id')
            ->select(
                'notifications.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderBy('notifications.created_at', 'desc')
            ->paginate(20);

        // Procesar los datos de las notificaciones
        $notifications->getCollection()->transform(function ($notification) {
            $data = json_decode($notification->data, true);
            $notification->type_name = class_basename($notification->type);
            $notification->title = $data['title'] ?? 'Sin título';
            $notification->message = $data['message'] ?? 'Sin mensaje';
            $notification->is_read = $notification->read_at !== null;
            return $notification;
        });

        return view('admin.notification-logs', compact('notifications'));
    }

    /**
     * Ver logs del sistema
     */
    public function systemLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];
        
        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $lines = explode("\n", $content);
            $lines = array_reverse($lines); // Mostrar más recientes primero
            
            foreach ($lines as $line) {
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/', $line, $matches)) {
                    $logs[] = [
                        'timestamp' => $matches[1],
                        'environment' => $matches[2],
                        'level' => $matches[3],
                        'message' => $matches[4]
                    ];
                }
            }
        }
        
        // Limitar a los últimos 100 logs
        $logs = array_slice($logs, 0, 100);
        
        return view('admin.system-logs', compact('logs'));
    }

    /**
     * Ver logs de acceso
     */
    public function accessLogs()
    {
        $logs = AccessLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $stats = [
            'total_accesses' => AccessLog::count(),
            'unique_users' => AccessLog::distinct('user_id')->whereNotNull('user_id')->count(),
            'failed_attempts' => AccessLog::where('success', false)->count(),
            'today_accesses' => AccessLog::whereDate('created_at', today())->count(),
        ];

        return view('admin.access-logs', compact('logs', 'stats'));
    }

    public function updateSettings(Request $request)
    {
        // Aquí irían configuraciones del sistema
        // Por ahora, placeholder
        return redirect()->back()->with('success', 'Configuración actualizada.');
    }

    /**
     * Visualización de Cadenas de Markov
     */
    public function markovChains()
    {
        // Obtener todas las habilidades
        $skills = \App\Models\Skill::all();

        // Obtener todas las transiciones
        $transitions = \App\Models\SkillTransition::with(['user', 'activity', 'fromSkill', 'toSkill'])
            ->orderBy('transition_date', 'desc')
            ->paginate(20);

        // Calcular matriz de transición
        $transitionMatrix = $this->calculateTransitionMatrix();

        // Estadísticas generales
        $stats = [
            'total_transitions' => \App\Models\SkillTransition::count(),
            'total_users_with_transitions' => \App\Models\SkillTransition::distinct('user_id')->count(),
            'total_level_ups' => \App\Models\SkillTransition::whereColumn('to_level', '>', 'from_level')->count(),
            'total_experience_gained' => \App\Models\SkillTransition::sum('experience_gained'),
            'avg_experience_per_transition' => \App\Models\SkillTransition::avg('experience_gained'),
        ];

        // Top usuarios con más transiciones
        $topUsers = \App\Models\User::withCount('skillTransitions')
            ->having('skill_transitions_count', '>', 0)
            ->orderBy('skill_transitions_count', 'desc')
            ->take(10)
            ->get();

        // Transiciones más comunes
        $commonTransitions = DB::table('skill_transitions')
            ->select('from_skill_id', 'to_skill_id', DB::raw('COUNT(*) as count'))
            ->groupBy('from_skill_id', 'to_skill_id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($transition) {
                return [
                    'from_skill' => \App\Models\Skill::find($transition->from_skill_id),
                    'to_skill' => \App\Models\Skill::find($transition->to_skill_id),
                    'count' => $transition->count,
                ];
            });

        return view('admin.markov.index', compact(
            'skills',
            'transitions',
            'transitionMatrix',
            'stats',
            'topUsers',
            'commonTransitions'
        ));
    }

    /**
     * Calcular matriz de transición de probabilidades
     */
    private function calculateTransitionMatrix()
    {
        $skills = \App\Models\Skill::all();
        $matrix = [];

        // Inicializar matriz
        foreach ($skills as $fromSkill) {
            $matrix[$fromSkill->id] = [];
            foreach ($skills as $toSkill) {
                $matrix[$fromSkill->id][$toSkill->id] = 0;
            }
        }

        // Contar transiciones
        $transitionCounts = DB::table('skill_transitions')
            ->select('from_skill_id', 'to_skill_id', DB::raw('COUNT(*) as count'))
            ->groupBy('from_skill_id', 'to_skill_id')
            ->get();

        // Calcular totales por habilidad origen
        $totalsByFromSkill = [];
        foreach ($transitionCounts as $transition) {
            if (!isset($totalsByFromSkill[$transition->from_skill_id])) {
                $totalsByFromSkill[$transition->from_skill_id] = 0;
            }
            $totalsByFromSkill[$transition->from_skill_id] += $transition->count;
        }

        // Calcular probabilidades
        foreach ($transitionCounts as $transition) {
            $fromSkillId = $transition->from_skill_id;
            $toSkillId = $transition->to_skill_id;
            $count = $transition->count;
            $total = $totalsByFromSkill[$fromSkillId] ?? 1;

            $probability = ($count / $total) * 100; // Porcentaje
            $matrix[$fromSkillId][$toSkillId] = round($probability, 2);
        }

        return [
            'skills' => $skills,
            'matrix' => $matrix,
        ];
    }

    /**
     * Obtener evolución de habilidades de un usuario específico
     */
    public function getUserSkillEvolution(Request $request)
    {
        $userId = $request->input('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $markovService = app(\App\Services\MarkovRecommendationService::class);
        $evolutionStats = $markovService->getSkillEvolutionStats($user);

        $transitions = \App\Models\SkillTransition::with(['fromSkill', 'toSkill', 'activity'])
            ->where('user_id', $userId)
            ->orderBy('transition_date', 'desc')
            ->get();

        return response()->json([
            'user' => $user,
            'stats' => $evolutionStats,
            'transitions' => $transitions,
        ]);
    }

    /**
     * Obtener datos de transiciones para gráficos
     */
    public function getMarkovChartData()
    {
        // Datos para gráfico de transiciones por mes
        $monthlyTransitions = DB::table('skill_transitions')
            ->selectRaw('DATE_FORMAT(transition_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Datos para gráfico de habilidades más desarrolladas
        $skillDevelopment = DB::table('skill_transitions')
            ->select('to_skill_id', DB::raw('SUM(experience_gained) as total_experience'))
            ->groupBy('to_skill_id')
            ->orderBy('total_experience', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $skill = \App\Models\Skill::find($item->to_skill_id);
                return [
                    'skill_name' => $skill ? $skill->name : 'Unknown',
                    'total_experience' => $item->total_experience,
                ];
            });

        return response()->json([
            'monthly_transitions' => $monthlyTransitions,
            'skill_development' => $skillDevelopment,
        ]);
    }

    /**
     * Show pending participation requests
     */
    public function pendingParticipations()
    {
        $pendingParticipations = Activity::with(['users' => function($query) {
            $query->wherePivot('status', 'pending');
        }])->whereHas('users', function($query) {
            $query->where('activity_user.status', 'pending');
        })->get();

        return view('admin.activities.pending-participations', compact('pendingParticipations'));
    }

    /**
     * Approve user participation in activity
     */
    public function approveParticipation(Activity $activity, User $user)
    {
        $activity->users()->updateExistingPivot($user->id, [
            'status' => 'joined'
        ]);
        
        // Send notification to user
        $user->notify(new \App\Notifications\ParticipationApprovedNotification($activity));
        
        return response()->json([
            'success' => true,
            'message' => 'Participación aprobada exitosamente.'
        ]);
    }

    /**
     * Reject user participation in activity
     */
    public function rejectParticipation(Request $request, Activity $activity, User $user)
    {
        $reason = $request->input('reason', 'No especificado');
        
        $activity->users()->detach($user->id);
        
        // Send notification to user with reason
        $user->notify(new \App\Notifications\ParticipationRejectedNotification($activity, $reason));
        
        return response()->json([
            'success' => true,
            'message' => 'Participación rechazada.'
        ]);
    }

    /**
     * Mark user participation as completed and award points
     */
    public function completeParticipation(Request $request, Activity $activity, User $user)
    {
        $request->validate([
            'attendance_percentage' => 'nullable|numeric|min:0|max:100',
            'completion_notes' => 'nullable|string|max:500',
            'custom_points' => 'nullable|integer|min:0|max:500'
        ]);

        $attendancePercentage = $request->input('attendance_percentage', 100);
        $completionNotes = $request->input('completion_notes');
        $customPoints = $request->input('custom_points');
        
        // Calculate points (use custom points if provided, otherwise calculate)
        if ($customPoints !== null) {
            $points = $customPoints;
        } else {
            $basePoints = $activity->getEstimatedPoints();
            $points = (int) ($basePoints * ($attendancePercentage / 100));
        }
        
        // Update participation
        $activity->users()->updateExistingPivot($user->id, [
            'status' => 'completed',
            'completed_at' => now(),
            'points_earned' => $points,
            'attendance_percentage' => $attendancePercentage,
            'completion_notes' => $completionNotes
        ]);
        
        // Award points to user
        $user->increment('points', $points);
        
        // Update skill experience
        $user->updateSkillExperienceFromActivity($activity);
        
        // Update user ranking
        $user->ranking?->updateStats();
        
        // Send notification
        $user->notify(new \App\Notifications\ActivityCompletedNotification($activity, $points));
        
        return response()->json([
            'success' => true,
            'message' => "Participación completada. {$user->name} ha ganado {$points} puntos.",
            'points_awarded' => $points
        ]);
    }

    /**
     * Bulk complete activity for all participants
     */
    public function bulkCompleteActivity(Request $request, Activity $activity)
    {
        $request->validate([
            'attendance_percentage' => 'nullable|numeric|min:0|max:100',
            'completion_notes' => 'nullable|string|max:500'
        ]);

        $attendancePercentage = $request->input('attendance_percentage', 100);
        $completionNotes = $request->input('completion_notes');
        
        $participants = $activity->users()->wherePivot('status', 'joined')->get();
        $totalPointsAwarded = 0;
        
        foreach ($participants as $user) {
            $basePoints = $activity->getEstimatedPoints();
            $points = (int) ($basePoints * ($attendancePercentage / 100));
            
            $activity->users()->updateExistingPivot($user->id, [
                'status' => 'completed',
                'completed_at' => now(),
                'points_earned' => $points,
                'attendance_percentage' => $attendancePercentage,
                'completion_notes' => $completionNotes
            ]);
            
            $user->increment('points', $points);
            $user->updateSkillExperienceFromActivity($activity);
            $user->ranking?->updateStats();
            $user->notify(new \App\Notifications\ActivityCompletedNotification($activity, $points));
            
            $totalPointsAwarded += $points;
        }
        
        // Mark activity as completed
        $activity->update(['status' => 'completed']);
        
        return response()->json([
            'success' => true,
            'message' => "Actividad completada para {$participants->count()} participantes. Total de puntos otorgados: {$totalPointsAwarded}",
            'participants_count' => $participants->count(),
            'total_points' => $totalPointsAwarded
        ]);
    }

    public function showParticipants(Activity $activity)
    {
        $participants = $activity->users()->withPivot('status', 'joined_at', 'completed_at')->get();
        return view('admin.activity-participants', compact('activity', 'participants'));
    }

    public function markParticipantCompleted(Activity $activity, User $user)
    {
        $activity->users()->updateExistingPivot($user->id, [
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        $points = $activity->getEstimatedPoints();
        $user->increment('points', $points);
        $user->updateSkillExperienceFromActivity($activity);
        $user->ranking?->updateStats();
        
        \App\Services\NotificationService::create(
            $user->id,
            'activity_completed',
            'Actividad Completada',
            "Has completado la actividad '{$activity->title}' y ganado {$points} puntos."
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Participante marcado como completado.'
        ]);
    }
}
