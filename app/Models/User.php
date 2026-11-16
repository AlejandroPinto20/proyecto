<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cod_est',
        'role',
        'phone',
        'bio',
        'points',
        'profile_photo_path',
        'is_active',
        'skills_completed',
        'password_changed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the activities the user is participating in.
     */
    public function participatingActivities()
    {
        return $this->belongsToMany(Activity::class)
            ->withPivot('status', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get the donations for the user.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the certificates for the user.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the ranking for the user.
     */
    public function ranking()
    {
        return $this->hasOne(Ranking::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is volunteer.
     */
    public function isVolunteer()
    {
        return $this->role === 'voluntario';
    }

    /**
     * Check if user is student.
     */
    public function isStudent()
    {
        return $this->role === 'estudiante';
    }

    /**
     * Check if user is active.
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * Get the skills for the user.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('level', 'experience_points', 'last_updated')
            ->withTimestamps()
            ->using(\App\Models\UserSkill::class);
    }

    /**
     * Get the skill transitions for the user.
     */
    public function skillTransitions()
    {
        return $this->hasMany(SkillTransition::class);
    }

    /**
     * Check if user has completed skills setup.
     */
    public function hasCompletedSkillsSetup()
    {
        return $this->skills_completed;
    }

    /**
     * Get or create user ranking.
     */
    public function getOrCreateRanking()
    {
        if (!$this->ranking) {
            $ranking = Ranking::create(['user_id' => $this->id]);
            $ranking->updateStats();
            return $ranking;
        }
        
        return $this->ranking;
    }

    /**
     * Check if user can make a new donation.
     */
    public function canMakeDonation()
    {
        // Los administradores siempre pueden hacer donaciones
        if ($this->isAdmin()) {
            return true;
        }
        
        return !$this->donations()
            ->whereIn('validation_status', ['pending', 'verifying'])
            ->exists();
    }

    /**
     * Get user's pending donation if exists.
     */
    public function getPendingDonation()
    {
        return $this->donations()
            ->whereIn('validation_status', ['pending', 'verifying'])
            ->first();
    }

    /**
     * Get count of unlocked achievements for the user.
     */
    public function getUnlockedAchievementsCount()
    {
        $achievements = [
            'first_activity' => $this->participatingActivities()->count() >= 1,
            'activity_enthusiast' => $this->participatingActivities()->count() >= 5,
            'activity_master' => $this->participatingActivities()->count() >= 15,
            'activity_legend' => $this->participatingActivities()->count() >= 30,
            'first_points' => $this->points >= 50,
            'point_collector' => $this->points >= 200,
            'point_master' => $this->points >= 500,
            'point_legend' => $this->points >= 1000,
            'early_bird' => $this->participatingActivities()->whereTime('activity_user.created_at', '<', '09:00:00')->count() >= 3,
            'night_owl' => $this->participatingActivities()->whereTime('activity_user.created_at', '>', '18:00:00')->count() >= 3,
            'weekend_warrior' => $this->participatingActivities()->whereIn(\DB::raw('DAYOFWEEK(activity_user.created_at)'), [1, 7])->count() >= 5,
            'skill_starter' => $this->skills()->count() >= 3,
            'skill_expert' => $this->skills()->wherePivot('level', '>=', 3)->count() >= 2,
            'skill_master' => $this->skills()->wherePivot('level', '>=', 5)->count() >= 1,
            'generous_heart' => $this->donations()->where('validation_status', 'approved')->count() >= 1,
            'super_donor' => $this->donations()->where('validation_status', 'approved')->sum('amount') >= 500,
            'unifranz_champion' => $this->participatingActivities()->count() >= 50 && $this->points >= 2000
        ];

        return collect($achievements)->filter()->count();
    }

    /**
     * Update skill experience when completing an activity.
     */
    public function updateSkillExperienceFromActivity(Activity $activity)
    {
        if (!$this->hasCompletedSkillsSetup()) {
            return;
        }

        $activitySkills = $activity->skills;
        if ($activitySkills->isEmpty()) {
            return;
        }

        $userSkills = $this->skills;
        if ($userSkills->isEmpty()) {
            return;
        }

        $hours = 1;
        if ($activity->start_date && $activity->end_date) {
            $hours = max(1, $activity->start_date->diffInHours($activity->end_date));
        }

        $baseXP = 10 * $hours;

        foreach ($activitySkills as $activitySkill) {
            $userSkill = $userSkills->firstWhere('id', $activitySkill->id);
            
            if ($userSkill) {
                $currentLevel = $userSkill->pivot->level;
                $currentXP = $userSkill->pivot->experience_points;
                $weight = $activitySkill->pivot->development_weight ?? 1.0;
                $xpGained = (int)($baseXP * $weight);
                $newXP = $currentXP + $xpGained;
                $xpForNextLevel = $currentLevel * 100;
                $newLevel = $currentLevel;
                
                if ($newXP >= $xpForNextLevel && $currentLevel < 5) {
                    $newLevel = $currentLevel + 1;
                }

                $this->skills()->updateExistingPivot($activitySkill->id, [
                    'level' => $newLevel,
                    'experience_points' => $newXP,
                    'last_updated' => now()
                ]);

                SkillTransition::create([
                    'user_id' => $this->id,
                    'activity_id' => $activity->id,
                    'from_skill_id' => $activitySkill->id,
                    'to_skill_id' => $activitySkill->id,
                    'from_level' => $currentLevel,
                    'to_level' => $newLevel,
                    'experience_gained' => $xpGained,
                    'transition_date' => now()
                ]);
            }
        }
    }
}
