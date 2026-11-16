<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Asegurar que el usuario tenga un ranking
        $userStats = $user->getOrCreateRanking();
        
        // All available achievements
        $allAchievements = $this->getAllAchievements();
        
        // User's unlocked achievements
        $unlockedAchievements = $this->getUserAchievements($user);
        $unlockedIds = collect($unlockedAchievements)->pluck('id')->toArray();
        
        // Mark achievements as unlocked
        foreach ($allAchievements as &$achievement) {
            $achievement['unlocked'] = in_array($achievement['id'], $unlockedIds);
            $achievement['progress'] = $this->getAchievementProgress($achievement, $userStats);
        }
        
        return view('achievements.index', compact('allAchievements', 'unlockedAchievements', 'userStats'));
    }
    
    private function getAllAchievements(): array
    {
        return [
            // Activity Achievements
            [
                'id' => 'first_step',
                'name' => 'Primer Paso',
                'icon' => '🎯',
                'description' => 'Completa tu primera actividad de voluntariado',
                'category' => 'Actividades',
                'requirement' => 'Completar 1 actividad',
                'points' => 10,
                'rarity' => 'common'
            ],
            [
                'id' => 'active_volunteer',
                'name' => 'Voluntario Activo',
                'icon' => '⭐',
                'description' => 'Demuestra tu compromiso completando 5 actividades',
                'category' => 'Actividades',
                'requirement' => 'Completar 5 actividades',
                'points' => 25,
                'rarity' => 'common'
            ],
            [
                'id' => 'veteran',
                'name' => 'Veterano',
                'icon' => '🏆',
                'description' => 'Un voluntario experimentado con 10 actividades completadas',
                'category' => 'Actividades',
                'requirement' => 'Completar 10 actividades',
                'points' => 50,
                'rarity' => 'uncommon'
            ],
            [
                'id' => 'champion',
                'name' => 'Campeón',
                'icon' => '👑',
                'description' => 'Elite del voluntariado con 25 actividades completadas',
                'category' => 'Actividades',
                'requirement' => 'Completar 25 actividades',
                'points' => 100,
                'rarity' => 'rare'
            ],
            [
                'id' => 'legend',
                'name' => 'Leyenda',
                'icon' => '🌟',
                'description' => 'Un verdadero héroe con 50 actividades completadas',
                'category' => 'Actividades',
                'requirement' => 'Completar 50 actividades',
                'points' => 200,
                'rarity' => 'legendary'
            ],
            
            // Points Achievements
            [
                'id' => 'centurion',
                'name' => 'Centurión',
                'icon' => '💯',
                'description' => 'Alcanza los primeros 100 puntos',
                'category' => 'Puntos',
                'requirement' => 'Obtener 100 puntos',
                'points' => 15,
                'rarity' => 'common'
            ],
            [
                'id' => 'point_master',
                'name' => 'Maestro de Puntos',
                'icon' => '💎',
                'description' => 'Acumula 500 puntos de experiencia',
                'category' => 'Puntos',
                'requirement' => 'Obtener 500 puntos',
                'points' => 50,
                'rarity' => 'uncommon'
            ],
            [
                'id' => 'point_legend',
                'name' => 'Leyenda de Puntos',
                'icon' => '🔥',
                'description' => 'Domina el sistema con 1000 puntos',
                'category' => 'Puntos',
                'requirement' => 'Obtener 1000 puntos',
                'points' => 100,
                'rarity' => 'rare'
            ],
            
            // Time Achievements
            [
                'id' => 'dedicated',
                'name' => 'Dedicado',
                'icon' => '⏰',
                'description' => 'Contribuye con 10 horas de voluntariado',
                'category' => 'Tiempo',
                'requirement' => 'Voluntariar 10 horas',
                'points' => 20,
                'rarity' => 'common'
            ],
            [
                'id' => 'committed',
                'name' => 'Comprometido',
                'icon' => '🕐',
                'description' => 'Dedica 50 horas a causas nobles',
                'category' => 'Tiempo',
                'requirement' => 'Voluntariar 50 horas',
                'points' => 75,
                'rarity' => 'uncommon'
            ],
            [
                'id' => 'time_master',
                'name' => 'Maestro del Tiempo',
                'icon' => '⌚',
                'description' => 'Invierte 100 horas en voluntariado',
                'category' => 'Tiempo',
                'requirement' => 'Voluntariar 100 horas',
                'points' => 150,
                'rarity' => 'rare'
            ],
            
            // Skill Achievements
            [
                'id' => 'multi_skilled',
                'name' => 'Multihabilidad',
                'icon' => '🎨',
                'description' => 'Desarrolla 3 habilidades diferentes',
                'category' => 'Habilidades',
                'requirement' => 'Tener 3+ habilidades',
                'points' => 30,
                'rarity' => 'common'
            ],
            [
                'id' => 'skill_expert',
                'name' => 'Experto en Habilidades',
                'icon' => '🧠',
                'description' => 'Alcanza nivel 4 en cualquier habilidad',
                'category' => 'Habilidades',
                'requirement' => 'Nivel 4 en una habilidad',
                'points' => 40,
                'rarity' => 'uncommon'
            ],
            [
                'id' => 'skill_master',
                'name' => 'Maestro de Habilidades',
                'icon' => '🎓',
                'description' => 'Domina una habilidad al nivel máximo',
                'category' => 'Habilidades',
                'requirement' => 'Nivel 5 en una habilidad',
                'points' => 60,
                'rarity' => 'rare'
            ],
            
            // Donation Achievements
            [
                'id' => 'generous',
                'name' => 'Generoso',
                'icon' => '❤️',
                'description' => 'Realiza tu primera donación',
                'category' => 'Donaciones',
                'requirement' => 'Hacer 1 donación',
                'points' => 25,
                'rarity' => 'common'
            ],
            [
                'id' => 'philanthropist',
                'name' => 'Filántropo',
                'icon' => '💝',
                'description' => 'Contribuye con 5 donaciones',
                'category' => 'Donaciones',
                'requirement' => 'Hacer 5 donaciones',
                'points' => 75,
                'rarity' => 'uncommon'
            ],
            
            // Special Achievements
            [
                'id' => 'early_bird',
                'name' => 'Madrugador',
                'icon' => '🌅',
                'description' => 'Únete a una actividad que inicia antes de las 8 AM',
                'category' => 'Especial',
                'requirement' => 'Actividad antes de 8 AM',
                'points' => 15,
                'rarity' => 'uncommon'
            ],
            [
                'id' => 'weekend_warrior',
                'name' => 'Guerrero de Fin de Semana',
                'icon' => '🏃',
                'description' => 'Participa en actividades durante 3 fines de semana consecutivos',
                'category' => 'Especial',
                'requirement' => '3 fines de semana seguidos',
                'points' => 35,
                'rarity' => 'rare'
            ],
            [
                'id' => 'category_explorer',
                'name' => 'Explorador de Categorías',
                'icon' => '🗺️',
                'description' => 'Participa en actividades de todas las categorías',
                'category' => 'Especial',
                'requirement' => 'Todas las categorías',
                'points' => 80,
                'rarity' => 'rare'
            ]
        ];
    }
    
    private function getUserAchievements($user): array
    {
        $achievements = [];
        $stats = $user->ranking;
        
        if (!$stats) return $achievements;
        
        // Activity achievements
        if ($stats->activities_completed >= 1) $achievements[] = ['id' => 'first_step'];
        if ($stats->activities_completed >= 5) $achievements[] = ['id' => 'active_volunteer'];
        if ($stats->activities_completed >= 10) $achievements[] = ['id' => 'veteran'];
        if ($stats->activities_completed >= 25) $achievements[] = ['id' => 'champion'];
        if ($stats->activities_completed >= 50) $achievements[] = ['id' => 'legend'];
        
        // Points achievements
        if ($stats->total_points >= 100) $achievements[] = ['id' => 'centurion'];
        if ($stats->total_points >= 500) $achievements[] = ['id' => 'point_master'];
        if ($stats->total_points >= 1000) $achievements[] = ['id' => 'point_legend'];
        
        // Time achievements
        if ($stats->hours_volunteered >= 10) $achievements[] = ['id' => 'dedicated'];
        if ($stats->hours_volunteered >= 50) $achievements[] = ['id' => 'committed'];
        if ($stats->hours_volunteered >= 100) $achievements[] = ['id' => 'time_master'];
        
        // Skill achievements
        $skillCount = $user->skills()->count();
        if ($skillCount >= 3) $achievements[] = ['id' => 'multi_skilled'];
        
        $maxSkillLevel = $user->skills()->withPivot('level')->get()->max('pivot.level') ?? 0;
        if ($maxSkillLevel >= 4) $achievements[] = ['id' => 'skill_expert'];
        if ($maxSkillLevel >= 5) $achievements[] = ['id' => 'skill_master'];
        
        // Donation achievements
        if ($stats->donations_made >= 1) $achievements[] = ['id' => 'generous'];
        if ($stats->donations_made >= 5) $achievements[] = ['id' => 'philanthropist'];
        
        return $achievements;
    }
    
    private function getAchievementProgress($achievement, $userStats): array
    {
        if (!$userStats) return ['current' => 0, 'target' => 1, 'percentage' => 0];
        
        switch ($achievement['id']) {
            case 'first_step': return ['current' => $userStats->activities_completed, 'target' => 1, 'percentage' => min(100, $userStats->activities_completed * 100)];
            case 'active_volunteer': return ['current' => $userStats->activities_completed, 'target' => 5, 'percentage' => min(100, ($userStats->activities_completed / 5) * 100)];
            case 'veteran': return ['current' => $userStats->activities_completed, 'target' => 10, 'percentage' => min(100, ($userStats->activities_completed / 10) * 100)];
            case 'champion': return ['current' => $userStats->activities_completed, 'target' => 25, 'percentage' => min(100, ($userStats->activities_completed / 25) * 100)];
            case 'legend': return ['current' => $userStats->activities_completed, 'target' => 50, 'percentage' => min(100, ($userStats->activities_completed / 50) * 100)];
            case 'centurion': return ['current' => $userStats->total_points, 'target' => 100, 'percentage' => min(100, ($userStats->total_points / 100) * 100)];
            case 'point_master': return ['current' => $userStats->total_points, 'target' => 500, 'percentage' => min(100, ($userStats->total_points / 500) * 100)];
            case 'point_legend': return ['current' => $userStats->total_points, 'target' => 1000, 'percentage' => min(100, ($userStats->total_points / 1000) * 100)];
            case 'dedicated': return ['current' => $userStats->hours_volunteered, 'target' => 10, 'percentage' => min(100, ($userStats->hours_volunteered / 10) * 100)];
            case 'committed': return ['current' => $userStats->hours_volunteered, 'target' => 50, 'percentage' => min(100, ($userStats->hours_volunteered / 50) * 100)];
            case 'time_master': return ['current' => $userStats->hours_volunteered, 'target' => 100, 'percentage' => min(100, ($userStats->hours_volunteered / 100) * 100)];
            default: return ['current' => 0, 'target' => 1, 'percentage' => 0];
        }
    }
}