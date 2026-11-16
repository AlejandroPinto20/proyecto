<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkill extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'skill_id',
        'level',
        'experience_points',
        'last_updated',
    ];

    protected $casts = [
        'level' => 'integer',
        'experience_points' => 'decimal:2',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the user that owns this skill.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skill.
     */
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Scope to get skills by level range.
     */
    public function scopeByLevel($query, $minLevel = 1, $maxLevel = 5)
    {
        return $query->whereBetween('level', [$minLevel, $maxLevel]);
    }

    /**
     * Scope to get skills with high experience points.
     */
    public function scopeExperienced($query, $minPoints = 100)
    {
        return $query->where('experience_points', '>=', $minPoints);
    }

    /**
     * Check if skill is at maximum level.
     */
    public function isMaxLevel()
    {
        return $this->level >= 5;
    }

    /**
     * Calculate experience needed for next level.
     */
    public function experienceForNextLevel()
    {
        $baseExperience = 100; // Base experience per level
        return $this->level * $baseExperience;
    }

    /**
     * Check if user can level up.
     */
    public function canLevelUp()
    {
        return !$this->isMaxLevel() && $this->experience_points >= $this->experienceForNextLevel();
    }
}
