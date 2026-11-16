<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    /**
     * Join an activity with improved logic
     */
    public function join(Activity $activity)
    {
        $user = Auth::user();

        // Validation checks
        if (!$activity->canUserJoin($user)) {
            return response()->json([
                'success' => false,
                'message' => $this->getJoinErrorMessage($activity, $user)
            ]);
        }

        // Determine initial status based on registration type
        $status = $activity->registration_type === 'approval_required' ? 'pending' : 'joined';
        
        $activity->users()->attach($user->id, [
            'status' => $status,
            'joined_at' => now()
        ]);

        if ($status === 'pending') {
            // Notify admins about new participation request
            $this->notifyAdminsNewRequest($activity, $user);
            
            return response()->json([
                'success' => true,
                'message' => 'Solicitud enviada. Esperando aprobación del administrador.',
                'status' => 'pending'
            ]);
        }

        // Crear notificación de unión exitosa
        NotificationService::activityJoined($user->id, $activity->title);

        return response()->json([
            'success' => true,
            'message' => 'Te has inscrito exitosamente a la actividad.',
            'status' => 'joined'
        ]);
    }

    /**
     * Leave an activity
     */
    public function leave(Activity $activity)
    {
        $user = Auth::user();

        if (!$activity->canUserLeave($user)) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes abandonar esta actividad en este momento.'
            ]);
        }

        $activity->users()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Has abandonado la actividad.'
        ]);
    }

    /**
     * Mark participation as completed (for point calculation)
     */
    public function markCompleted(Activity $activity, User $user = null)
    {
        $user = $user ?? Auth::user();
        
        $participation = $activity->users()->where('user_id', $user->id)->first();
        
        if (!$participation) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no está inscrito en esta actividad.'
            ]);
        }

        // Update participation status to completed
        $activity->users()->updateExistingPivot($user->id, [
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Calculate and award points
        $points = $this->calculateActivityPoints($activity);
        $user->increment('points', $points);

        // Update ranking
        $user->ranking?->updateStats();

        // Crear notificación de actividad completada
        NotificationService::create(
            $user->id,
            'activity_completed',
            'Actividad Completada',
            "Has completado la actividad '{$activity->title}' y ganado {$points} puntos."
        );

        return response()->json([
            'success' => true,
            'message' => "Participación completada. Has ganado {$points} puntos.",
            'points_earned' => $points
        ]);
    }

    /**
     * Calculate points for activity completion
     */
    private function calculateActivityPoints(Activity $activity): int
    {
        $basePoints = 20; // Base points for any activity
        
        // Category multipliers
        $categoryMultipliers = [
            'voluntariado' => 1.5,
            'capacitacion' => 1.2,
            'evento' => 1.0,
            'taller' => 1.3,
            'reunion' => 0.8,
            'proyecto' => 2.0,
        ];

        // Duration bonus (if activity has start and end dates)
        $durationBonus = 0;
        if ($activity->start_date && $activity->end_date) {
            $hours = $activity->start_date->diffInHours($activity->end_date);
            $durationBonus = min($hours * 2, 50); // Max 50 bonus points
        }

        // Leadership bonus (if user is the organizer)
        $leadershipBonus = Auth::id() === $activity->user_id ? 30 : 0;

        $multiplier = $categoryMultipliers[$activity->category] ?? 1.0;
        
        return (int) (($basePoints + $durationBonus + $leadershipBonus) * $multiplier);
    }

    /**
     * Get appropriate error message for join attempt
     */
    private function getJoinErrorMessage(Activity $activity, User $user): string
    {
        if ($activity->users()->where('user_id', $user->id)->exists()) {
            return 'Ya estás inscrito en esta actividad.';
        }

        if ($activity->isCancelled()) {
            return 'Esta actividad ha sido cancelada.';
        }

        if ($activity->isCompleted()) {
            return 'Esta actividad ya ha finalizado.';
        }

        if ($activity->isFull()) {
            return 'Esta actividad está llena.';
        }

        if (!$activity->isActive() && !$activity->isPending()) {
            return 'Esta actividad no está disponible para inscripciones.';
        }

        return 'No puedes inscribirte en esta actividad en este momento.';
    }

    /**
     * Notify admins about new participation request
     */
    private function notifyAdminsNewRequest(Activity $activity, User $user): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewParticipationRequestNotification($activity, $user));
        }
    }
}