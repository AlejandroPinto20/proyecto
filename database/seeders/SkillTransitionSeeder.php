<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;
use App\Models\Skill;
use App\Models\SkillTransition;
use Carbon\Carbon;

class SkillTransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios (excluyendo admins)
        $users = User::where('role', '!=', 'admin')->get();
        
        // Obtener habilidades
        $skills = Skill::all();
        
        // Obtener actividades
        $activities = Activity::all();
        
        if ($users->isEmpty() || $skills->isEmpty() || $activities->isEmpty()) {
            $this->command->warn('No hay suficientes datos. Asegúrate de ejecutar UserSeeder, SkillSeeder y ActivitySeeder primero.');
            return;
        }
        
        $this->command->info('Creando transiciones de habilidades de prueba...');
        
        // Crear transiciones para cada usuario
        foreach ($users as $user) {
            // Asignar habilidades iniciales al usuario si no tiene
            if ($user->skills()->count() === 0) {
                $initialSkills = $skills->random(rand(2, 4));
                foreach ($initialSkills as $skill) {
                    $user->skills()->attach($skill->id, [
                        'level' => 1,
                        'experience_points' => 0,
                        'last_updated' => now(),
                    ]);
                }
                $user->update(['skills_completed' => true]);
            }
            
            // Crear entre 5 y 15 transiciones por usuario
            $numTransitions = rand(5, 15);
            $userSkills = $user->skills()->get();
            
            for ($i = 0; $i < $numTransitions; $i++) {
                // Seleccionar habilidad origen (de las que tiene el usuario)
                $fromSkill = $userSkills->random();
                
                // 70% de probabilidad de que sea la misma habilidad (desarrollo progresivo)
                // 30% de probabilidad de que sea una habilidad diferente
                if (rand(1, 100) <= 70) {
                    $toSkill = $fromSkill;
                } else {
                    $toSkill = $skills->random();
                    // Si el usuario no tiene esta habilidad, agregarla
                    if (!$userSkills->contains($toSkill->id)) {
                        $user->skills()->attach($toSkill->id, [
                            'level' => 1,
                            'experience_points' => 0,
                            'last_updated' => now(),
                        ]);
                        $userSkills = $user->skills()->get(); // Actualizar lista
                    }
                }
                
                // Obtener niveles actuales
                $fromLevel = $user->skills()->where('skill_id', $fromSkill->id)->first()->pivot->level;
                $currentExp = $user->skills()->where('skill_id', $toSkill->id)->first()->pivot->experience_points;
                
                // Calcular experiencia ganada (entre 10 y 50 puntos)
                $expGained = rand(10, 50) / 10; // 1.0 a 5.0
                
                // Calcular nuevo nivel
                $newExp = $currentExp + $expGained;
                $toLevel = $fromLevel;
                
                // Verificar si sube de nivel (cada 100 puntos = 1 nivel, máximo nivel 5)
                $expForNextLevel = $fromLevel * 100;
                if ($newExp >= $expForNextLevel && $fromLevel < 5) {
                    $toLevel = $fromLevel + 1;
                    $newExp = $newExp - $expForNextLevel;
                }
                
                // Actualizar habilidad del usuario
                $user->skills()->updateExistingPivot($toSkill->id, [
                    'level' => $toLevel,
                    'experience_points' => $newExp,
                    'last_updated' => now(),
                ]);
                
                // Crear transición con fecha aleatoria en los últimos 6 meses
                $transitionDate = Carbon::now()->subDays(rand(0, 180));
                
                SkillTransition::create([
                    'user_id' => $user->id,
                    'activity_id' => $activities->random()->id,
                    'from_skill_id' => $fromSkill->id,
                    'to_skill_id' => $toSkill->id,
                    'from_level' => $fromLevel,
                    'to_level' => $toLevel,
                    'experience_gained' => $expGained,
                    'transition_date' => $transitionDate,
                ]);
            }
        }
        
        $totalTransitions = SkillTransition::count();
        $this->command->info("✅ Se crearon {$totalTransitions} transiciones de habilidades exitosamente!");
        
        // Mostrar estadísticas
        $this->command->info("\n📊 Estadísticas:");
        $this->command->info("- Usuarios con transiciones: " . User::whereHas('skillTransitions')->count());
        $this->command->info("- Total de transiciones: " . $totalTransitions);
        $this->command->info("- Subidas de nivel: " . SkillTransition::whereColumn('to_level', '>', 'from_level')->count());
        $this->command->info("- Experiencia total ganada: " . number_format(SkillTransition::sum('experience_gained'), 2));
    }
}
