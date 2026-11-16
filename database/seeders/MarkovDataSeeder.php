<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;
use App\Models\Skill;
use App\Models\SkillTransition;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MarkovDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@unifranz.edu.bo')->first();
        $skills = Skill::all();
        
        // Nombres realistas de estudiantes bolivianos
        $students = [
            ['name' => 'Carlos Mamani', 'cod_est' => 'EST001'],
            ['name' => 'María Quispe', 'cod_est' => 'EST002'],
            ['name' => 'Juan Condori', 'cod_est' => 'EST003'],
            ['name' => 'Ana Flores', 'cod_est' => 'EST004'],
            ['name' => 'Pedro Choque', 'cod_est' => 'EST005'],
            ['name' => 'Laura Apaza', 'cod_est' => 'EST006'],
            ['name' => 'Diego Vargas', 'cod_est' => 'EST007'],
            ['name' => 'Sofia Morales', 'cod_est' => 'EST008'],
            ['name' => 'Luis Gutierrez', 'cod_est' => 'EST009'],
            ['name' => 'Valentina Rojas', 'cod_est' => 'EST010'],
        ];

        $createdUsers = [];
        
        // Crear usuarios estudiantes
        foreach ($students as $student) {
            $email = strtolower(str_replace(' ', '.', $student['name'])) . '@unifranz.edu.bo';
            
            // Verificar si ya existe
            $user = User::where('email', $email)->first();
            
            if ($user) {
                $createdUsers[] = $user;
                continue;
            }
            
            $user = User::create([
                'name' => $student['name'],
                'email' => $email,
                'password' => Hash::make($student['cod_est']),
                'role' => 'estudiante',
                'cod_est' => $student['cod_est'],
                'phone' => '7' . rand(1000000, 9999999),
                'points' => 0,
                'is_active' => true,
                'password_changed' => false,
            ]);
            
            // Crear ranking
            \App\Models\Ranking::create(['user_id' => $user->id]);
            
            // Asignar habilidades iniciales con niveles bajos
            foreach ($skills->random(rand(3, 5)) as $skill) {
                $user->skills()->attach($skill->id, [
                    'level' => 1,
                    'experience_points' => rand(0, 50),
                ]);
            }
            
            $createdUsers[] = $user;
        }

        // Obtener actividades existentes
        $activities = Activity::all();
        
        if ($activities->isEmpty()) {
            $this->command->warn('No hay actividades. Ejecuta ActivitySeeder primero.');
            return;
        }

        // Simular participación y completación de actividades
        foreach ($createdUsers as $user) {
            // Cada usuario participa en 3-6 actividades
            $userActivities = $activities->random(rand(3, 6));
            
            foreach ($userActivities as $activity) {
                // Inscribir en la actividad
                $joinedDate = Carbon::now()->subDays(rand(10, 60));
                $completedDate = $joinedDate->copy()->addDays(rand(1, 7));
                $activity->users()->attach($user->id, [
                    'status' => 'completed',
                    'joined_at' => $joinedDate,
                    'completed_at' => $completedDate,
                    'points_earned' => $activity->getEstimatedPoints(),
                ]);
                
                // Actualizar puntos del usuario
                $user->increment('points', $activity->getEstimatedPoints());
                
                // Generar transiciones de habilidades
                $activitySkills = $activity->skills;
                
                if ($activitySkills->isNotEmpty()) {
                    foreach ($activitySkills as $activitySkill) {
                        // Obtener habilidad actual del usuario
                        $userSkill = $user->skills()->where('skill_id', $activitySkill->id)->first();
                        
                        if (!$userSkill) {
                            // Si no tiene la habilidad, agregarla
                            $user->skills()->attach($activitySkill->id, [
                                'level' => 1,
                                'experience_points' => 0,
                            ]);
                            $userSkill = $user->skills()->where('skill_id', $activitySkill->id)->first();
                        }
                        
                        $fromLevel = $userSkill->pivot->level;
                        $fromExperience = $userSkill->pivot->experience_points;
                        
                        // Calcular experiencia ganada
                        $hours = $activity->start_date && $activity->end_date 
                            ? $activity->start_date->diffInHours($activity->end_date) 
                            : 4;
                        $developmentWeight = $activitySkill->pivot->development_weight ?? 0.7;
                        $experienceGained = $hours * 10 * $developmentWeight;
                        
                        $newExperience = $fromExperience + $experienceGained;
                        $newLevel = $fromLevel;
                        
                        // Subir de nivel si es necesario
                        while ($newExperience >= ($newLevel * 100) && $newLevel < 5) {
                            $newExperience -= ($newLevel * 100);
                            $newLevel++;
                        }
                        
                        // Actualizar habilidad del usuario
                        $user->skills()->updateExistingPivot($activitySkill->id, [
                            'level' => $newLevel,
                            'experience_points' => $newExperience,
                        ]);
                        
                        // Crear transición
                        SkillTransition::create([
                            'user_id' => $user->id,
                            'activity_id' => $activity->id,
                            'from_skill_id' => $activitySkill->id,
                            'to_skill_id' => $activitySkill->id,
                            'from_level' => $fromLevel,
                            'to_level' => $newLevel,
                            'experience_gained' => $experienceGained,
                            'transition_date' => $completedDate,
                        ]);
                        
                        // Crear transiciones cruzadas (cambio de habilidad)
                        if (rand(1, 3) == 1 && $activitySkills->count() > 1) {
                            $otherSkill = $activitySkills->where('id', '!=', $activitySkill->id)->random();
                            
                            SkillTransition::create([
                                'user_id' => $user->id,
                                'activity_id' => $activity->id,
                                'from_skill_id' => $activitySkill->id,
                                'to_skill_id' => $otherSkill->id,
                                'from_level' => $fromLevel,
                                'to_level' => $fromLevel,
                                'experience_gained' => $experienceGained * 0.5,
                                'transition_date' => $joinedDate->copy()->addDays(rand(1, 7)),
                            ]);
                        }
                    }
                }
            }
            
            // Actualizar ranking del usuario
            $user->ranking->updateStats();
        }
        
        $this->command->info('✅ Seeder de Markov completado:');
        $this->command->info('   - ' . count($createdUsers) . ' estudiantes creados');
        $this->command->info('   - ' . SkillTransition::count() . ' transiciones generadas');
    }
}
