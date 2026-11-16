<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Skill;
use App\Services\MarkovRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    protected $recommendationService;

    public function __construct(MarkovRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display a listing of the resource with calendar integration.
     */
    public function index(Request $request)
    {
        $view = $request->get('view', 'grid'); // grid, calendar, list
        
        $query = Activity::query();
        
        // Base filter - show active activities and user's enrolled activities for non-admins
        if (!Auth::user()->isAdmin()) {
            $query->where(function($q) {
                $q->whereIn('status', ['abierta', 'proxima'])
                  ->orWhereHas('users', function($userQuery) {
                      $userQuery->where('user_id', Auth::id());
                  });
            });
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }
        
        // Status filter (for admins)
        if (Auth::user()->isAdmin() && $request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->get('date_to'));
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination for grid/list view
        if ($view === 'calendar') {
            $activities = $query->with(['user', 'users', 'skills'])->get();
        } else {
            $activities = $query->with(['user', 'users', 'skills'])->paginate(12)->withQueryString();
        }
        
        // Get recommendations for non-admin users
        if (Auth::user()->isAdmin()) {
            $recommendations = collect();
        } else {
            $recommendations = $this->recommendationService->getRecommendationsForUser(Auth::user(), 3);
        }
        
        // Get user's current ranking
        $userRanking = null;
        if (!Auth::user()->isAdmin()) {
            $userRanking = Auth::user()->ranking;
            if (!$userRanking) {
                $userRanking = \App\Models\Ranking::create(['user_id' => Auth::id()]);
                $userRanking->updateStats();
            }
            
            // Calculate user's position using the same logic as RankingController
            $userPosition = \App\Models\Ranking::whereHas('user', function($query) {
                    $query->where('role', '!=', 'admin')
                          ->where('is_active', true);
                })
                ->where('total_points', '>', $userRanking->total_points)
                ->count() + 1;
            
            $userRanking->rank_position = $userPosition;
        }
        
        // Get filter options
        $categories = [
            'educacion' => 'Educación',
            'medio_ambiente' => 'Medio Ambiente',
            'salud' => 'Salud',
            'social' => 'Social',
            'comunitario' => 'Comunitario'
        ];
        
        $statuses = [
            'abierta' => 'Abierta',
            'proxima' => 'Próxima',
            'en_progreso' => 'En Progreso',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada'
        ];
        
        return view('activities.index', compact('activities', 'recommendations', 'userRanking', 'categories', 'statuses', 'view'));
    }

    /**
     * Display the history of activities (completed and cancelled).
     */
    public function history()
    {
        if (Auth::user()->isAdmin()) {
            $activities = Activity::whereIn('status', ['completada', 'cancelada', 'completed', 'cancelled'])->orderBy('end_date', 'desc')->get();
        } else {
            $activities = Auth::user()->participatingActivities()->whereIn('activities.status', ['completada', 'cancelada', 'completed', 'cancelled'])->orderBy('end_date', 'desc')->get();
        }
        return view('activities.history', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skills = Skill::active()->orderBy('category')->orderBy('name')->get();
        return view('activities.create', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:voluntariado,capacitacion,evento,taller,proyecto',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'max_participants' => 'nullable|integer|min:1|max:1000',
            'status' => 'required|in:abierta,proxima,en_progreso,completada,cancelada',
            'registration_type' => 'required|in:open,approval_required',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id'
        ]);

        $data = $request->only([
            'title',
            'description',
            'category',
            'registration_type',
            'start_date',
            'end_date',
            'location',
            'latitude',
            'longitude',
            'max_participants',
            'status'
        ]);
        $data['name'] = $request->title;
        
        $activity = Auth::user()->activities()->create($data);

        // Attach selected skills
        if ($request->has('skills')) {
            $activity->skills()->sync($request->input('skills', []));
        }

        // Send notification to all users about new activity
        if ($activity->start_date) {
            \Notification::send(\App\Models\User::all(), new \App\Notifications\NewActivityNotification($activity));
        }

        // Check if it's an AJAX request
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Actividad creada exitosamente.',
                'activity' => $activity
            ]);
        }

        return redirect()->route('activities.index')->with('success', 'Actividad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $skills = Skill::active()->orderBy('category')->orderBy('name')->get();
        return view('activities.edit', compact('activity', 'skills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:educacion,medio_ambiente,salud,social,comunitario,voluntariado,capacitacion,evento,taller,proyecto',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:abierta,proxima,en_progreso,completada,cancelada',
            'registration_type' => 'required|in:open,approval_required',
        ]);

        $activity->update($request->only([
            'name',
            'description',
            'category',
            'registration_type',
            'start_date',
            'end_date',
            'location',
            'max_participants',
            'status'
        ]));

        return redirect()->route('activities.index')->with('success', 'Actividad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        // Check if activity is completed
        if ($activity->isCompleted()) {
            return redirect()->route('activities.index')->with('error', 'No se puede eliminar una actividad que ya está completada.');
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Actividad eliminada exitosamente.');
    }

    /**
     * Get activities for calendar (JSON response for AJAX)
     */
    public function getActivities(Request $request)
    {
        $query = Activity::with(['user', 'users'])
            ->whereNotNull('start_date')
            ->whereNotNull('end_date');

        $activities = $query->get();

        $events = $activities->map(function ($activity) {
            $color = match($activity->status) {
                'abierta' => '#10b981',        // 🟢 Verde - Aceptando participantes
                'proxima' => '#f59e0b',        // 🟡 Amarillo - Por iniciar
                'en_progreso' => '#3b82f6',   // 🔵 Azul - En ejecución
                'completada' => '#22c55e',    // ✅ Verde claro - Finalizada
                'cancelada' => '#ef4444',     // 🔴 Rojo - No se realizó
                default => '#10b981',         // Verde por defecto
            };

            return [
                'id' => $activity->id,
                'title' => $activity->name,
                'start' => $activity->start_date->toISOString(),
                'end' => $activity->end_date->toISOString(),
                'description' => $activity->description,
                'location' => $activity->location,
                'status' => $activity->status,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
            ];
        });

        return response()->json($events);
    }

    /**
     * Join an activity
     */
    public function join(Activity $activity)
    {
        $user = Auth::user();

        if (!in_array($activity->status, ['abierta', 'active', 'proxima'])) {
            return response()->json([
                'success' => false,
                'message' => 'Esta actividad no está disponible para inscripción (Status: ' . $activity->status . ').'
            ]);
        }

        if ($activity->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás participando en esta actividad.'
            ]);
        }

        if ($activity->isFull()) {
            return response()->json([
                'success' => false,
                'message' => 'La actividad está llena.'
            ]);
        }

        $status = $activity->registration_type === 'approval_required' ? 'pending' : 'joined';
        $activity->users()->attach($user->id, ['status' => $status, 'joined_at' => now()]);
        
        // Update user ranking if joined directly
        if ($status === 'joined') {
            $this->updateUserRanking($user);
        }
        
        return response()->json([
            'success' => true,
            'message' => $status === 'pending' ? 'Solicitud enviada.' : 'Te has unido exitosamente.',
            'points_info' => $status === 'joined' ? 'Ganarás ' . $activity->getEstimatedPoints() . ' puntos al completar esta actividad.' : null
        ]);
    }

    /**
     * Leave an activity
     */
    public function leave(Activity $activity)
    {
        $user = Auth::user();

        if (!$activity->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No estás participando en esta actividad.'
            ]);
        }

        $activity->users()->detach($user->id);
        
        // Update user ranking
        $this->updateUserRanking($user);

        return response()->json([
            'success' => true,
            'message' => 'Has abandonado la actividad.'
        ]);
    }

    /**
     * Complete an activity and award points
     */
    public function completeActivity(Activity $activity, $userId = null)
    {
        $user = $userId ? \App\Models\User::find($userId) : Auth::user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.']);
        }

        $participation = $activity->users()->where('user_id', $user->id)->first();
        
        if (!$participation) {
            return response()->json(['success' => false, 'message' => 'El usuario no está participando en esta actividad.']);
        }

        // Update participation status to completed
        $activity->users()->updateExistingPivot($user->id, [
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Award points and update ranking
        $points = $activity->getEstimatedPoints();
        $this->awardPoints($user, $points, 'activity_completion', $activity->id);
        
        return response()->json([
            'success' => true,
            'message' => "Actividad completada. Se han otorgado {$points} puntos.",
            'points_awarded' => $points
        ]);
    }

    /**
     * Award points to user and update ranking
     */
    private function awardPoints($user, $points, $reason, $activityId = null)
    {
        // Create or get user ranking
        $ranking = \App\Models\Ranking::firstOrCreate(['user_id' => $user->id]);
        
        // Log the points transaction
        \App\Models\PointTransaction::create([
            'user_id' => $user->id,
            'activity_id' => $activityId,
            'points' => $points,
            'reason' => $reason,
            'created_at' => now()
        ]);
        
        // Update ranking stats
        $ranking->updateStats();
    }

    /**
     * Update user ranking
     */
    private function updateUserRanking($user)
    {
        $ranking = \App\Models\Ranking::firstOrCreate(['user_id' => $user->id]);
        $ranking->updateStats();
    }
}
