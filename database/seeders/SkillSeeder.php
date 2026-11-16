<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Habilidades Técnicas
            [
                'name' => 'Programación Web',
                'slug' => 'programacion-web',
                'description' => 'Desarrollo de aplicaciones web usando HTML, CSS, JavaScript y frameworks modernos',
                'category' => 'técnica',
                'is_active' => true,
            ],
            [
                'name' => 'Base de Datos',
                'slug' => 'base-datos',
                'description' => 'Diseño, gestión y optimización de bases de datos relacionales y no relacionales',
                'category' => 'técnica',
                'is_active' => true,
            ],
            [
                'name' => 'Desarrollo Móvil',
                'slug' => 'desarrollo-movil',
                'description' => 'Creación de aplicaciones móviles para iOS y Android',
                'category' => 'técnica',
                'is_active' => true,
            ],
            [
                'name' => 'Inteligencia Artificial',
                'slug' => 'inteligencia-artificial',
                'description' => 'Aplicación de algoritmos de IA, machine learning y análisis de datos',
                'category' => 'técnica',
                'is_active' => true,
            ],
            [
                'name' => 'Ciberseguridad',
                'slug' => 'ciberseguridad',
                'description' => 'Protección de sistemas informáticos y datos contra amenazas digitales',
                'category' => 'técnica',
                'is_active' => true,
            ],

            // Habilidades Sociales
            [
                'name' => 'Liderazgo',
                'slug' => 'liderazgo',
                'description' => 'Capacidad para guiar equipos y tomar decisiones estratégicas',
                'category' => 'social',
                'is_active' => true,
            ],
            [
                'name' => 'Comunicación',
                'slug' => 'comunicacion',
                'description' => 'Habilidades para expresarse efectivamente verbal y escrito',
                'category' => 'social',
                'is_active' => true,
            ],
            [
                'name' => 'Trabajo en Equipo',
                'slug' => 'trabajo-equipo',
                'description' => 'Colaboración efectiva con otros para lograr objetivos comunes',
                'category' => 'social',
                'is_active' => true,
            ],
            [
                'name' => 'Resolución de Conflictos',
                'slug' => 'resolucion-conflictos',
                'description' => 'Manejo constructivo de desacuerdos y situaciones difíciles',
                'category' => 'social',
                'is_active' => true,
            ],
            [
                'name' => 'Empatía',
                'slug' => 'empatia',
                'description' => 'Capacidad para entender y compartir los sentimientos de los demás',
                'category' => 'social',
                'is_active' => true,
            ],

            // Habilidades Organizacionales
            [
                'name' => 'Gestión de Proyectos',
                'slug' => 'gestion-proyectos',
                'description' => 'Planificación, ejecución y control de proyectos complejos',
                'category' => 'organizacional',
                'is_active' => true,
            ],
            [
                'name' => 'Organización de Eventos',
                'slug' => 'organizacion-eventos',
                'description' => 'Planificación y coordinación de eventos comunitarios',
                'category' => 'organizacional',
                'is_active' => true,
            ],
            [
                'name' => 'Administración de Recursos',
                'slug' => 'administracion-recursos',
                'description' => 'Gestión eficiente de recursos humanos, materiales y financieros',
                'category' => 'organizacional',
                'is_active' => true,
            ],
            [
                'name' => 'Análisis de Datos',
                'slug' => 'analisis-datos',
                'description' => 'Interpretación de datos para tomar decisiones informadas',
                'category' => 'organizacional',
                'is_active' => true,
            ],
            [
                'name' => 'Innovación',
                'slug' => 'innovacion',
                'description' => 'Capacidad para generar ideas creativas y soluciones novedosas',
                'category' => 'organizacional',
                'is_active' => true,
            ],

            // Habilidades Creativas
            [
                'name' => 'Diseño Gráfico',
                'slug' => 'diseno-grafico',
                'description' => 'Creación de contenido visual atractivo y efectivo',
                'category' => 'creativa',
                'is_active' => true,
            ],
            [
                'name' => 'Fotografía',
                'slug' => 'fotografia',
                'description' => 'Captura y edición de imágenes para diversos propósitos',
                'category' => 'creativa',
                'is_active' => true,
            ],
            [
                'name' => 'Redacción',
                'slug' => 'redaccion',
                'description' => 'Escritura efectiva de textos para diferentes audiencias',
                'category' => 'creativa',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Digital',
                'slug' => 'marketing-digital',
                'description' => 'Estrategias de promoción y comunicación en entornos digitales',
                'category' => 'creativa',
                'is_active' => true,
            ],
            [
                'name' => 'Producción Audiovisual',
                'slug' => 'produccion-audiovisual',
                'description' => 'Creación de contenido multimedia para comunicación',
                'category' => 'creativa',
                'is_active' => true,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
