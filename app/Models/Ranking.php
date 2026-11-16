<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ranking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_points',
        'activities_completed',
        'hours_volunteered',
        'donations_made',
        'certificates_earned',
        'rank',
        'rank_position',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'activities_completed' => 'integer',
        'hours_volunteered' => 'integer',
        'donations_made' => 'integer',
        'certificates_earned' => 'integer',
        'rank_position' => 'integer',
    ];

    /**
     * Get the user that owns the ranking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate rank based on total points.
     */
    public function calculateRank(): string
    {
        $points = $this->total_points;

        if ($points >= 8000) {
            return 'Diamante';
        } elseif ($points >= 4000) {
            return 'Platino';
        } elseif ($points >= 1500) {
            return 'Oro';
        } elseif ($points >= 600) {
            return 'Plata';
        } else {
            return 'Bronce';
        }
    }

    /**
     * Update ranking statistics.
     */
    public function updateStats(): void
    {
        $user = $this->user;

        // Count completed activities (where user participated and status is completed)
        $this->activities_completed = $user->participatingActivities()
            ->wherePivot('status', 'completed')
            ->count();

        // Calculate hours from completed activities
        $completedActivities = $user->participatingActivities()
            ->wherePivot('status', 'completed')
            ->get();
        
        $totalHours = 0;
        foreach ($completedActivities as $activity) {
            if ($activity->start_date && $activity->end_date) {
                $hours = $activity->start_date->diffInHours($activity->end_date);
                $totalHours += $hours;
            }
        }
        $this->hours_volunteered = $totalHours;

        // Count verified and approved donations
        $approvedDonations = $user->donations()
            ->where('validation_status', 'approved')
            ->where('status', 'approved')
            ->get();
        $this->donations_made = $approvedDonations->count();
            
        $this->certificates_earned = $user->certificates()->count();

        // Calculate points with new system:
        // - 15 points per activity completed
        // - 8 points per hour of volunteering  
        // - Variable points per donation based on amount
        // - 75 points per certificate
        // - Achievement bonus points
        
        $activityPoints = $this->activities_completed * 15;
        $hourPoints = $this->hours_volunteered * 8;
        $donationPoints = $approvedDonations->sum(function($donation) {
            return $donation->calculatePoints();
        });
        $certificatePoints = $this->certificates_earned * 75;
        $achievementPoints = $this->calculateAchievementPoints($user);
        
        $this->total_points = $activityPoints + $hourPoints + $donationPoints + $certificatePoints + $achievementPoints;

        $this->rank = $this->calculateRank();
        $this->save();
        
        // Sincronizar puntos en la tabla users
        $user->update(['points' => $this->total_points]);
    }

    /**
     * Get top rankings.
     */
    public static function getTopRankings(int $limit = 10)
    {
        $rankings = self::with('user')
            ->whereHas('user', function($query) {
                $query->where('role', '!=', 'admin')
                      ->where('is_active', true);
            })
            ->orderBy('total_points', 'desc')
            ->orderBy('hours_volunteered', 'desc')
            ->orderBy('activities_completed', 'desc')
            ->take($limit)
            ->get();

        // Update positions
        foreach ($rankings as $index => $ranking) {
            $ranking->rank_position = $index + 1;
            $ranking->save();
        }

        return $rankings;
    }

    /**
     * Update all rankings for all users.
     */
    public static function updateAllRankings(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $ranking = self::firstOrCreate(['user_id' => $user->id]);
            $ranking->updateStats();
        }

        // Update positions
        self::getTopRankings(50); // Update top 50 positions
    }

    /**
     * Calculate achievement bonus points
     */
    private function calculateAchievementPoints($user): int
    {
        $achievementPoints = 0;
        $stats = $this;
        
        // Activity achievements
        if ($stats->activities_completed >= 1) $achievementPoints += 10; // Primer Paso
        if ($stats->activities_completed >= 5) $achievementPoints += 25; // Voluntario Activo
        if ($stats->activities_completed >= 10) $achievementPoints += 50; // Veterano
        if ($stats->activities_completed >= 25) $achievementPoints += 100; // Campeón
        if ($stats->activities_completed >= 50) $achievementPoints += 200; // Leyenda
        
        // Points achievements (recursive but capped)
        $basePoints = $stats->total_points - $achievementPoints;
        if ($basePoints >= 100) $achievementPoints += 15; // Centurión
        if ($basePoints >= 500) $achievementPoints += 50; // Maestro de Puntos
        if ($basePoints >= 1000) $achievementPoints += 100; // Leyenda de Puntos
        
        // Time achievements
        if ($stats->hours_volunteered >= 10) $achievementPoints += 20; // Dedicado
        if ($stats->hours_volunteered >= 50) $achievementPoints += 75; // Comprometido
        if ($stats->hours_volunteered >= 100) $achievementPoints += 150; // Maestro del Tiempo
        
        // Skill achievements
        $skillCount = $user->skills()->count();
        if ($skillCount >= 3) $achievementPoints += 30; // Multihabilidad
        
        $maxSkillLevel = $user->skills()->withPivot('level')->get()->max('pivot.level') ?? 0;
        if ($maxSkillLevel >= 4) $achievementPoints += 40; // Experto en Habilidades
        if ($maxSkillLevel >= 5) $achievementPoints += 60; // Maestro de Habilidades
        
        // Donation achievements
        if ($stats->donations_made >= 1) $achievementPoints += 25; // Generoso
        if ($stats->donations_made >= 5) $achievementPoints += 75; // Filántropo
        
        return $achievementPoints;
    }

    /**
     * Get user's unlocked achievements count
     */
    public function getUnlockedAchievementsCount(): int
    {
        $count = 0;
        $user = $this->user;
        
        // Activity achievements
        if ($this->activities_completed >= 1) $count++;
        if ($this->activities_completed >= 5) $count++;
        if ($this->activities_completed >= 10) $count++;
        if ($this->activities_completed >= 25) $count++;
        if ($this->activities_completed >= 50) $count++;
        
        // Points achievements
        if ($this->total_points >= 100) $count++;
        if ($this->total_points >= 500) $count++;
        if ($this->total_points >= 1000) $count++;
        
        // Time achievements
        if ($this->hours_volunteered >= 10) $count++;
        if ($this->hours_volunteered >= 50) $count++;
        if ($this->hours_volunteered >= 100) $count++;
        
        // Skill achievements
        $skillCount = $user->skills()->count();
        if ($skillCount >= 3) $count++;
        
        $maxSkillLevel = $user->skills()->withPivot('level')->get()->max('pivot.level') ?? 0;
        if ($maxSkillLevel >= 4) $count++;
        if ($maxSkillLevel >= 5) $count++;
        
        // Donation achievements
        if ($this->donations_made >= 1) $count++;
        if ($this->donations_made >= 5) $count++;
        
        return $count;
    }
}
