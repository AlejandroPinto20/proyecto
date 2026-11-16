<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'from_skill_id',
        'to_skill_id',
        'from_level',
        'to_level',
        'experience_gained',
        'transition_date',
    ];

    protected $casts = [
        'from_level' => 'integer',
        'to_level' => 'integer',
        'experience_gained' => 'decimal:2',
        'transition_date' => 'datetime',
    ];

    /**
     * Get the user that made this transition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity that caused this transition.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the skill from which the transition started.
     */
    public function fromSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'from_skill_id');
    }

    /**
     * Get the skill to which the transition went.
     */
    public function toSkill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'to_skill_id');
    }

    /**
     * Scope to get transitions for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get transitions for a specific activity.
     */
    public function scopeForActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Scope to get transitions within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transition_date', [$startDate, $endDate]);
    }

    /**
     * Check if this was a level up transition.
     */
    public function isLevelUp()
    {
        return $this->to_level > $this->from_level;
    }

    /**
     * Check if this was a skill change transition.
     */
    public function isSkillChange()
    {
        return $this->from_skill_id !== $this->to_skill_id;
    }
}
