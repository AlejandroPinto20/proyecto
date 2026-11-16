<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\MarkovRecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected $recommendationService;

    public function __construct(MarkovRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's enhanced profile with skills.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // Get user skills with pivot data
        $userSkills = $user->skills()->withPivot('level', 'experience_points', 'last_updated')->get();

        // Get personalized recommendations
        $recommendations = collect();
        if (!$user->isAdmin()) {
            $recommendations = $this->recommendationService->getRecommendationsForUser($user, 5);
        }

        // Get user ranking and stats
        $userRanking = null;
        if (!$user->isAdmin()) {
            $userRanking = $user->ranking;
            if (!$userRanking) {
                $userRanking = \App\Models\Ranking::create(['user_id' => $user->id]);
            }
            $userRanking->updateStats();
        }

        // Get recent activities with proper relationship
        $recentActivities = $user->activities()
            ->latest()
            ->take(5)
            ->get();

        // Get activity statistics
        $activityStats = [
            'total' => $user->activities()->count(),
            'completed' => $userRanking ? $userRanking->activities_completed : 0,
            'in_progress' => $user->activities()->count() - ($userRanking ? $userRanking->activities_completed : 0),
            'points_earned' => $userRanking ? $userRanking->total_points : 0
        ];

        // Get skill development insights
        $skillInsights = $this->getSkillInsights($user);

        // Get achievement badges
        $achievements = $this->getUserAchievements($user);

        return view('profile.show', compact(
            'user', 
            'userSkills', 
            'recommendations', 
            'userRanking',
            'recentActivities',
            'activityStats',
            'skillInsights',
            'achievements'
        ));
    }

    /**
     * Get skill development insights
     */
    private function getSkillInsights($user): array
    {
        $skills = $user->skills;
        
        return [
            'total_skills' => $skills->count(),
            'advanced_skills' => $skills->where('pivot.level', '>=', 4)->count(),
            'developing_skills' => $skills->whereBetween('pivot.level', [2, 3])->count(),
            'beginner_skills' => $skills->where('pivot.level', '<=', 1)->count(),
            'total_experience' => $skills->sum('pivot.experience_points'),
            'skill_categories' => $skills->groupBy('category')->map->count()->toArray()
        ];
    }

    /**
     * Get user achievements/badges
     */
    private function getUserAchievements($user): array
    {
        $achievements = [];
        $stats = $user->ranking;
        
        if (!$stats) return $achievements;
        
        // Activity-based achievements
        if ($stats->activities_completed >= 1) {
            $achievements[] = ['name' => 'Primer Paso', 'icon' => '🎯', 'description' => 'Completaste tu primera actividad'];
        }
        if ($stats->activities_completed >= 5) {
            $achievements[] = ['name' => 'Voluntario Activo', 'icon' => '⭐', 'description' => 'Completaste 5 actividades'];
        }
        if ($stats->activities_completed >= 10) {
            $achievements[] = ['name' => 'Veterano', 'icon' => '🏆', 'description' => 'Completaste 10 actividades'];
        }
        
        // Points-based achievements
        if ($stats->total_points >= 100) {
            $achievements[] = ['name' => 'Centurión', 'icon' => '💯', 'description' => 'Alcanzaste 100 puntos'];
        }
        if ($stats->total_points >= 500) {
            $achievements[] = ['name' => 'Campeón', 'icon' => '👑', 'description' => 'Alcanzaste 500 puntos'];
        }
        
        // Time-based achievements
        if ($stats->hours_volunteered >= 10) {
            $achievements[] = ['name' => 'Dedicado', 'icon' => '⏰', 'description' => 'Contribuiste 10+ horas'];
        }
        if ($stats->hours_volunteered >= 50) {
            $achievements[] = ['name' => 'Comprometido', 'icon' => '🕐', 'description' => 'Contribuiste 50+ horas'];
        }
        
        // Skill-based achievements
        $skillCount = $user->skills()->count();
        if ($skillCount >= 3) {
            $achievements[] = ['name' => 'Multihabilidad', 'icon' => '🎨', 'description' => 'Desarrollaste 3+ habilidades'];
        }
        
        // Donation achievements
        if ($stats->donations_made >= 1) {
            $achievements[] = ['name' => 'Generoso', 'icon' => '❤️', 'description' => 'Realizaste tu primera donación'];
        }
        
        return $achievements;
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $message = 'Información del perfil actualizada correctamente.';

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo_path && \Storage::disk('public')->exists($user->profile_photo_path)) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new profile photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $message = 'Foto de perfil subida correctamente.';
        }

        $validated = $request->validated();
        
        // Add country code to phone if provided
        if (isset($validated['phone']) && $validated['phone']) {
            $validated['phone'] = '+591' . $validated['phone'];
        }
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show')->with('status', $message);
    }



    /**
     * Request account deactivation.
     */
    public function requestDeactivation(Request $request): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $user = $request->user();

        // Here you could send an email to admins or store the deactivation request
        // For now, we'll just mark the user as inactive and log them out

        $user->update(['is_active' => false]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Solicitud de desactivación enviada. Un administrador revisará tu solicitud.');
    }
}
