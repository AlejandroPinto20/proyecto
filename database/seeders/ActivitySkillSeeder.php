<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class ActivitySkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las actividades existentes
        $activities = Activity::all();

        if ($activities->isEmpty()) {
            return; // No hay actividades para asignar skills
        }

        // Obtener skills por categoría
        $technicalSkills = Skill::where('category', 'técnica')->pluck('id')->toArray();
        $socialSkills = Skill::where('category', 'social')->pluck('id')->toArray();
        $organizationalSkills = Skill::where('category', 'organizacional')->pluck('id')->toArray();
        $creativeSkills = Skill::where('category', 'creativa')->pluck('id')->toArray();

        // Asignar skills a actividades basadas en su nombre/descripción
        foreach ($activities as $activity) {
            $skillsToAssign = [];

            $name = strtolower($activity->name);
            $description = strtolower($activity->description ?? '');

            // Lógica de asignación basada en palabras clave
            if (str_contains($name . $description, 'programa') || str_contains($name . $description, 'web') || str_contains($name . $description, 'desarrollo')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($technicalSkills), 'weight' => rand(3, 5)];
            }

            if (str_contains($name . $description, 'base de datos') || str_contains($name . $description, 'datos')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($technicalSkills), 'weight' => rand(3, 5)];
            }

            if (str_contains($name . $description, 'equipo') || str_contains($name . $description, 'colaboración') || str_contains($name . $description, 'grupo')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($socialSkills), 'weight' => rand(2, 4)];
            }

            if (str_contains($name . $description, 'lider') || str_contains($name . $description, 'dirigir')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($socialSkills), 'weight' => rand(3, 5)];
            }

            if (str_contains($name . $description, 'evento') || str_contains($name . $description, 'organiza')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($organizationalSkills), 'weight' => rand(3, 5)];
            }

            if (str_contains($name . $description, 'proyecto') || str_contains($name . $description, 'gestion')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($organizationalSkills), 'weight' => rand(2, 4)];
            }

            if (str_contains($name . $description, 'diseño') || str_contains($name . $description, 'creativ')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($creativeSkills), 'weight' => rand(3, 5)];
            }

            if (str_contains($name . $description, 'comunica') || str_contains($name . $description, 'redacción')) {
                $skillsToAssign[] = ['skill_id' => $this->getRandomSkill($creativeSkills), 'weight' => rand(2, 4)];
            }

            // Si no se encontraron skills específicos, asignar algunos aleatorios
            if (empty($skillsToAssign)) {
                $allSkills = array_merge($technicalSkills, $socialSkills, $organizationalSkills, $creativeSkills);
                $numSkills = rand(1, 3);
                $randomSkills = array_rand(array_flip($allSkills), min($numSkills, count($allSkills)));

                if (!is_array($randomSkills)) {
                    $randomSkills = [$randomSkills];
                }

                foreach ($randomSkills as $skillId) {
                    $skillsToAssign[] = ['skill_id' => $skillId, 'weight' => rand(1, 3)];
                }
            }

            // Evitar duplicados y asignar a la actividad
            $uniqueSkills = [];
            foreach ($skillsToAssign as $skillData) {
                if (!in_array($skillData['skill_id'], $uniqueSkills)) {
                    $activity->skills()->attach($skillData['skill_id'], [
                        'development_weight' => $skillData['weight']
                    ]);
                    $uniqueSkills[] = $skillData['skill_id'];
                }
            }
        }
    }

    /**
     * Get a random skill from an array of skill IDs.
     */
    private function getRandomSkill(array $skills): int
    {
        return $skills[array_rand($skills)];
    }
}
