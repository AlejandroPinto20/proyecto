<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Services\MarkovRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Skill;

class CalendarController extends Controller
{
    protected $recommendationService;

    public function __construct(MarkovRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the calendar view with activities and history tabs
     */
    public function index()
    {
        // Get all activities for the list view (including all statuses)
        $activities = Activity::with(['user', 'users'])->orderBy('created_at', 'desc')->get();
        
        // Debug: Log activities count
        \Log::info('Calendar index - Activities count: ' . $activities->count());
        
        if (Auth::user()->isAdmin()) {
            $recommendations = collect(); // Admin no necesita recomendaciones
        } else {
            $recommendations = $this->recommendationService->getRecommendationsForUser(Auth::user(), 5);
        }

        // Get history activities
        if (Auth::user()->isAdmin()) {
            $historyActivities = Activity::whereIn('status', ['completed', 'cancelled'])->orderBy('end_date', 'desc')->get();
        } else {
            $historyActivities = Auth::user()->activities()->whereIn('status', ['completed', 'cancelled'])->orderBy('end_date', 'desc')->get();
        }

        // Load user's skills for the calendar view (used for recommendations and display)
        $skills = Auth::user()->skills()->orderBy('name')->get();
        
        // Load all available skills for activity creation
        $allSkills = collect(); // Simplificado temporalmente
        try {
            $allSkills = Skill::orderBy('name')->get()->map(function($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'emoji' => '🔧',
                    'category' => 'general'
                ];
            });
        } catch (\Exception $e) {
            \Log::error('Error loading skills: ' . $e->getMessage());
        }

        // Calculate statistics
        $statistics = $this->calculateStatistics($activities);

        return view('calendar.index', compact('activities', 'historyActivities', 'recommendations', 'skills', 'allSkills', 'statistics'));
    }

    /**
     * Calculate statistics for the dashboard
     */
    private function calculateStatistics($activities)
    {
        $user = Auth::user();
        
        return [
            'total_activities' => $activities->count(),
            'active_activities' => $activities->where('status', 'active')->count(),
            'pending_activities' => $activities->where('status', 'pending')->count(),
            'completed_activities' => $activities->where('status', 'completed')->count(),
            'cancelled_activities' => $activities->where('status', 'cancelled')->count(),
            'my_participations' => $user->activities()->count(),
            'my_completed' => $user->activities()->where('status', 'completed')->count(),
            'upcoming_this_week' => $activities->filter(function($activity) {
                return $activity->start_date && 
                       $activity->start_date->isAfter(now()) && 
                       $activity->start_date->isBefore(now()->addWeek());
            })->count(),
            'categories' => $activities->groupBy('category')->map->count(),
            'monthly_trend' => $this->getMonthlyTrend($activities),
        ];
    }

    /**
     * Get monthly trend data for activities
     */
    private function getMonthlyTrend($activities)
    {
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = $activities->filter(function($activity) use ($month) {
                return $activity->created_at && 
                       $activity->created_at->format('Y-m') === $month->format('Y-m');
            })->count();
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        
        return $monthlyData;
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
                'active' => '#10b981', // green
                'pending' => '#f59e0b', // yellow
                'completed' => '#6b7280', // gray
                'cancelled' => '#ef4444', // red
                default => '#3b82f6', // blue
            };

            return [
                'id' => $activity->id,
                'title' => $activity->name,
                'start' => $activity->start_date->toISOString(),
                'end' => $activity->end_date->toISOString(),
                'description' => $activity->description,
                'location' => $activity->location,
                'max_participants' => $activity->max_participants,
                'current_participants' => $activity->current_participants,
                'status' => $activity->status,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'organizer' => $activity->user->name,
                    'participants' => $activity->users->pluck('name')->toArray(),
                    'is_full' => $activity->isFull(),
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Show activity details (JSON response for modal)
     */
    public function show(Activity $activity)
    {
        $activity->load(['user', 'users']);

        return response()->json([
            'id' => $activity->id,
            'name' => $activity->name,
            'description' => $activity->description,
            'category' => $activity->category,
            'status' => $activity->status,
            'start_date' => $activity->start_date,
            'end_date' => $activity->end_date,
            'location' => $activity->location,
            'max_participants' => $activity->max_participants,
            'current_participants' => $activity->users->count(),
            'organizer' => $activity->user->name,
            'participants' => $activity->users->pluck('id')->toArray(),
            'is_full' => $activity->max_participants ? $activity->users->count() >= $activity->max_participants : false,
        ]);
    }

    /**
     * Join an activity
     */
    public function join(Activity $activity)
    {
        $user = Auth::user();

        // Check if activity is available for joining
        if (!in_array($activity->status, ['active'])) {
            return response()->json([
                'success' => false,
                'message' => 'Esta actividad no está disponible para unirse. Estado: ' . ucfirst($activity->status)
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

        if ($activity->registration_type === 'approval_required') {
            $activity->users()->attach($user->id, ['status' => 'pending', 'joined_at' => now()]);
            
            // Notify admins about new participation request
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\NewParticipationRequestNotification($activity, $user));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Solicitud enviada. Esperando aprobación del administrador.'
            ]);
        } else {
            $activity->users()->attach($user->id, ['status' => 'joined', 'joined_at' => now()]);
            return response()->json([
                'success' => true,
                'message' => 'Te has unido a la actividad exitosamente.'
            ]);
        }
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

        return response()->json([
            'success' => true,
            'message' => 'Has abandonado la actividad.'
        ]);
    }
}
