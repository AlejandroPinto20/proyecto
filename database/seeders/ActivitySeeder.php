<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Skill;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = \App\Models\User::where('email', 'admin@unifranz.edu.bo')->first();
        $skills = Skill::all();
        
        $activities = [
            [
                'title' => 'Taller de Programación Web',
                'description' => 'Aprende los fundamentos de desarrollo web con HTML, CSS y JavaScript. Ideal para principiantes.',
                'category' => 'taller',
                'status' => 'abierta',
                'location' => 'Aula 301, Campus Unifranz',
                'max_participants' => 30,
                'start_date' => Carbon::now()->addDays(7)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(7)->setTime(18, 0),
                'skills' => ['Desarrollo Web', 'Programación'],
            ],
            [
                'title' => 'Campaña de Reforestación',
                'description' => 'Únete a nuestra jornada de plantación de árboles en el parque ecológico. Contribuye al medio ambiente.',
                'category' => 'voluntariado',
                'status' => 'abierta',
                'location' => 'Parque Ecológico Laikakota',
                'max_participants' => 50,
                'start_date' => Carbon::now()->addDays(10)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(10)->setTime(13, 0),
                'skills' => ['Trabajo en Equipo', 'Liderazgo'],
            ],
            [
                'title' => 'Hackathon Universitario 2024',
                'description' => 'Competencia de 24 horas para desarrollar soluciones tecnológicas innovadoras. Premios para los mejores proyectos.',
                'category' => 'evento',
                'status' => 'proxima',
                'location' => 'Centro de Innovación Unifranz',
                'max_participants' => 100,
                'start_date' => Carbon::now()->addDays(15)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(16)->setTime(9, 0),
                'skills' => ['Programación', 'Desarrollo Web', 'Trabajo en Equipo'],
            ],
            [
                'title' => 'Capacitación en Primeros Auxilios',
                'description' => 'Curso certificado de primeros auxilios básicos. Aprende a responder en situaciones de emergencia.',
                'category' => 'capacitacion',
                'status' => 'abierta',
                'location' => 'Auditorio Principal',
                'max_participants' => 40,
                'start_date' => Carbon::now()->addDays(5)->setTime(15, 0),
                'end_date' => Carbon::now()->addDays(5)->setTime(19, 0),
                'skills' => ['Comunicación', 'Liderazgo'],
            ],
            [
                'title' => 'Proyecto de Alfabetización Digital',
                'description' => 'Enseña habilidades básicas de computación a adultos mayores de la comunidad.',
                'category' => 'proyecto',
                'status' => 'abierta',
                'location' => 'Centro Comunitario Villa Fátima',
                'max_participants' => 20,
                'start_date' => Carbon::now()->addDays(12)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(12)->setTime(14, 0),
                'skills' => ['Comunicación', 'Trabajo en Equipo', 'Liderazgo'],
            ],
            [
                'title' => 'Taller de Diseño UX/UI',
                'description' => 'Aprende los principios de diseño de experiencia de usuario y diseño de interfaces.',
                'category' => 'taller',
                'status' => 'proxima',
                'location' => 'Laboratorio de Diseño',
                'max_participants' => 25,
                'start_date' => Carbon::now()->addDays(20)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(20)->setTime(18, 0),
                'skills' => ['Diseño Gráfico', 'Desarrollo Web'],
            ],
            [
                'title' => 'Feria de Emprendimiento Social',
                'description' => 'Evento para presentar y apoyar emprendimientos con impacto social. Networking y mentorías.',
                'category' => 'evento',
                'status' => 'abierta',
                'location' => 'Plaza Central Unifranz',
                'max_participants' => 80,
                'start_date' => Carbon::now()->addDays(8)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(8)->setTime(17, 0),
                'skills' => ['Liderazgo', 'Comunicación', 'Trabajo en Equipo'],
            ],
            [
                'title' => 'Limpieza de Playas Lago Titicaca',
                'description' => 'Jornada de limpieza y concientización ambiental en las orillas del Lago Titicaca.',
                'category' => 'voluntariado',
                'status' => 'proxima',
                'location' => 'Copacabana',
                'max_participants' => 60,
                'start_date' => Carbon::now()->addDays(18)->setTime(7, 0),
                'end_date' => Carbon::now()->addDays(18)->setTime(15, 0),
                'skills' => ['Trabajo en Equipo', 'Liderazgo'],
            ],
            [
                'title' => 'Workshop de Inteligencia Artificial',
                'description' => 'Introducción práctica a Machine Learning y redes neuronales con Python.',
                'category' => 'capacitacion',
                'status' => 'abierta',
                'location' => 'Laboratorio de Computación',
                'max_participants' => 35,
                'start_date' => Carbon::now()->addDays(14)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(14)->setTime(19, 0),
                'skills' => ['Programación', 'Análisis de Datos'],
            ],
            [
                'title' => 'Campaña de Donación de Sangre',
                'description' => 'Jornada de donación voluntaria de sangre en colaboración con el Banco de Sangre.',
                'category' => 'evento',
                'status' => 'abierta',
                'location' => 'Enfermería Unifranz',
                'max_participants' => 100,
                'start_date' => Carbon::now()->addDays(6)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(6)->setTime(14, 0),
                'skills' => ['Comunicación', 'Trabajo en Equipo'],
            ],
        ];

        foreach ($activities as $activityData) {
            $skillNames = $activityData['skills'];
            unset($activityData['skills']);
            $activityData['user_id'] = $admin->id;

            $activity = Activity::create($activityData);

            // Asignar habilidades con peso aleatorio
            foreach ($skillNames as $skillName) {
                $skill = $skills->firstWhere('name', $skillName);
                if ($skill) {
                    $activity->skills()->attach($skill->id, [
                        'development_weight' => rand(5, 10) / 10,
                    ]);
                }
            }
        }
    }
}
