<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/** @use HasFactory<\Database\Factories\ActivityFactory> */
class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'user_id',
        'status',
        'category',
        'registration_type',
        'start_date',
        'end_date',
        'location',
        'latitude',
        'longitude',
        'max_participants',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get the current number of participants
     */
    public function getCurrentParticipantsAttribute()
    {
        return $this->users()->wherePivot('status', 'joined')->count();
    }

    /**
     * Check if activity is full
     */
    public function isFull()
    {
        if (!$this->max_participants) {
            return false;
        }
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Check if activity is upcoming
     */
    public function isUpcoming()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    /**
     * Check if activity is ongoing
     */
    public function isOngoing()
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }
        return now()->between($this->start_date, $this->end_date);
    }

    /**
     * Check if activity is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed' || ($this->end_date && $this->end_date->isPast());
    }

    /**
     * Check if activity is active (can be joined)
     */
    public function isActive()
    {
        return in_array($this->status, ['active', 'abierta', 'proxima']);
    }

    /**
     * Check if activity is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if activity is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if user can join this activity
     */
    public function canUserJoin(User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return false;
        }

        // Can't join if already participating
        if ($this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Can't join cancelled or completed activities
        if ($this->isCancelled() || $this->isCompleted()) {
            return false;
        }

        // Can't join if activity is full
        if ($this->isFull()) {
            return false;
        }

        // Check if registration is still open
        if ($this->start_date && $this->start_date->isPast()) {
            return false;
        }

        // Can join active, abierta, or proxima activities
        return in_array($this->status, ['active', 'abierta', 'proxima']);
    }

    /**
     * Check if user can leave this activity
     */
    public function canUserLeave(User $user = null)
    {
        // If no user provided, check if current user can leave
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return false;
        }

        // Can only leave if participating
        if (!$this->users()->where('user_id', $user->id)->exists()) {
            return false;
        }

        // Can't leave completed activities (for record keeping)
        if ($this->isCompleted()) {
            return false;
        }

        // Can't leave cancelled activities
        if ($this->isCancelled()) {
            return false;
        }

        return true;
    }

    /**
     * Get the skills that this activity develops.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'activity_skills')
            ->withPivot('development_weight')
            ->withTimestamps();
    }

    /**
     * Get the skill transitions for this activity.
     */
    public function skillTransitions()
    {
        return $this->hasMany(SkillTransition::class);
    }

    /**
     * Get emoji representation for the activity category.
     */
    public function getCategoryEmoji()
    {
        $emojis = [
            'voluntariado' => '🤝',
            'capacitacion' => '📚',
            'evento' => '🎉',
            'taller' => '🔧',
            'reunion' => '👥',
            'proyecto' => '🚀',
            'educacion' => '🎓',
            'medio_ambiente' => '🌱',
            'salud' => '🏥',
            'social' => '👥',
            'comunitario' => '🏘️',
        ];

        return $emojis[$this->category] ?? '📅';
    }

    /**
     * Get user's participation status in this activity
     */
    public function getUserParticipationStatus(User $user = null): ?string
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return null;
        }

        $participation = $this->users()->where('user_id', $user->id)->first();
        return $participation ? $participation->pivot->status : null;
    }

    /**
     * Get activity difficulty level based on duration and category
     */
    public function getDifficultyLevel(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'media';
        }

        $hours = $this->start_date->diffInHours($this->end_date);
        
        $categoryDifficulty = [
            'proyecto' => 3,
            'voluntariado' => 2,
            'taller' => 2,
            'capacitacion' => 1,
            'evento' => 1,
            'reunion' => 1,
        ];

        $baseDifficulty = $categoryDifficulty[$this->category] ?? 2;
        
        if ($hours <= 2) {
            $timeDifficulty = 1;
        } elseif ($hours <= 8) {
            $timeDifficulty = 2;
        } else {
            $timeDifficulty = 3;
        }

        $totalDifficulty = ($baseDifficulty + $timeDifficulty) / 2;

        if ($totalDifficulty <= 1.5) {
            return 'fácil';
        } elseif ($totalDifficulty <= 2.5) {
            return 'media';
        } else {
            return 'difícil';
        }
    }

    /**
     * Get estimated points for completing this activity
     */
    public function getEstimatedPoints(): int
    {
        $basePoints = 20;
        
        $categoryMultipliers = [
            'voluntariado' => 1.5,
            'capacitacion' => 1.2,
            'evento' => 1.0,
            'taller' => 1.3,
            'reunion' => 0.8,
            'proyecto' => 2.0,
            'educacion' => 1.2,
            'medio_ambiente' => 1.4,
            'salud' => 1.3,
            'social' => 1.2,
            'comunitario' => 1.4,
        ];

        $durationBonus = 0;
        if ($this->start_date && $this->end_date) {
            $hours = $this->start_date->diffInHours($this->end_date);
            $durationBonus = min($hours * 2, 50);
        }

        $multiplier = $categoryMultipliers[$this->category] ?? 1.0;
        
        return (int) (($basePoints + $durationBonus) * $multiplier);
    }
}
