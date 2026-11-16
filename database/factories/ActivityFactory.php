<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +8 hours');

        $actividades = [
            ['nombre' => 'Campaña de Reforestación', 'desc' => 'Jornada de plantación de árboles nativos en áreas verdes de la ciudad para combatir el cambio climático.'],
            ['nombre' => 'Apoyo Escolar Comunitario', 'desc' => 'Clases de refuerzo en matemáticas y lenguaje para niños de escuelas públicas de zonas periféricas.'],
            ['nombre' => 'Jornada de Salud Preventiva', 'desc' => 'Campaña de atención médica gratuita y charlas sobre hábitos saludables en comunidades rurales.'],
            ['nombre' => 'Limpieza de Espacios Públicos', 'desc' => 'Actividad de recolección de residuos y limpieza en plazas y parques de la ciudad.'],
            ['nombre' => 'Taller de Alfabetización Digital', 'desc' => 'Enseñanza de habilidades básicas de computación e internet para adultos mayores.'],
            ['nombre' => 'Donación de Alimentos', 'desc' => 'Recolección y distribución de alimentos no perecederos para familias en situación de vulnerabilidad.'],
            ['nombre' => 'Campaña de Reciclaje', 'desc' => 'Promoción de la separación de residuos y educación ambiental en barrios de la ciudad.'],
            ['nombre' => 'Apoyo a Adultos Mayores', 'desc' => 'Visitas y acompañamiento a personas de la tercera edad en hogares y centros geriátricos.'],
            ['nombre' => 'Construcción de Viviendas Sociales', 'desc' => 'Participación en proyectos de construcción de casas para familias de bajos recursos.'],
            ['nombre' => 'Biblioteca Móvil Comunitaria', 'desc' => 'Promoción de la lectura llevando libros a comunidades sin acceso a bibliotecas.'],
        ];

        $ubicaciones = [
            'Av. Cristo Redentor, Santa Cruz de la Sierra',
            'Plaza 24 de Septiembre, Santa Cruz',
            'Parque Urbano, 4to Anillo',
            'Universidad Franz Tamayo - UNIFRANZ, Santa Cruz',
            'Centro Cultural Santa Cruz',
            'Barrio Equipetrol, Santa Cruz',
            'Villa 1ro de Mayo, Santa Cruz',
            'Plan 3000, Santa Cruz',
            'Parque El Arenal, Santa Cruz',
            'Av. Banzer, Santa Cruz de la Sierra',
        ];

        $actividad = fake()->randomElement($actividades);

        return [
            'name' => $actividad['nombre'],
            'description' => $actividad['desc'],
            'user_id' => \App\Models\User::factory(),
            'status' => fake()->randomElement(['abierta', 'proxima', 'en_progreso', 'completada', 'cancelada']),
            'category' => fake()->randomElement(['voluntariado', 'capacitacion', 'evento', 'taller', 'proyecto']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->randomElement($ubicaciones),
            'max_participants' => fake()->numberBetween(5, 50),
        ];
    }
}
