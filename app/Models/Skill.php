<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that have this skill.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('level', 'experience_points', 'last_updated')
            ->withTimestamps();
    }

    /**
     * Get the activities that develop this skill.
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_skills')
            ->withPivot('development_weight')
            ->withTimestamps();
    }

    /**
     * Get skill transitions where this skill was the starting point.
     */
    public function fromTransitions()
    {
        return $this->hasMany(SkillTransition::class, 'from_skill_id');
    }

    /**
     * Get skill transitions where this skill was the target.
     */
    public function toTransitions()
    {
        return $this->hasMany(SkillTransition::class, 'to_skill_id');
    }

    /**
     * Scope to get only active skills.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get emoji representation for the skill.
     */
    public function getEmoji()
    {
        $emojis = [
            'técnica' => '💻',
            'social' => '🤝',
            'organizacional' => '📋',
            'creativa' => '🎨',
        ];

        return $emojis[$this->category] ?? '🎯';
    }
}
