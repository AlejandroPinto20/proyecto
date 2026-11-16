<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = ['Juan', 'María', 'Carlos', 'Ana', 'Luis', 'Carmen', 'José', 'Laura', 'Pedro', 'Sofia', 'Diego', 'Valentina', 'Miguel', 'Isabella', 'Andrés', 'Camila', 'Roberto', 'Daniela', 'Fernando', 'Gabriela'];
        $apellidos = ['García', 'Rodríguez', 'López', 'Martínez', 'González', 'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez', 'Díaz', 'Cruz', 'Morales', 'Reyes', 'Gutiérrez', 'Ortiz', 'Mendoza', 'Vargas'];
        
        $nombre = fake()->randomElement($nombres) . ' ' . fake()->randomElement($apellidos) . ' ' . fake()->randomElement($apellidos);
        $phone = '7' . fake()->numerify('#######');
        $hasCodEst = fake()->boolean(70);
        $codEst = $hasCodEst ? fake()->unique()->numerify('EST-#####') : null;
        
        $bios = [
            'Estudiante comprometido con el voluntariado y el servicio comunitario.',
            'Me apasiona ayudar a los demás y contribuir al desarrollo de mi comunidad.',
            'Busco generar un impacto positivo a través del voluntariado universitario.',
            'Interesado en proyectos sociales y actividades de responsabilidad social.',
            'Comprometido con el cambio social y el desarrollo sostenible.',
            'Participante activo en iniciativas de voluntariado y acción social.',
        ];

        return [
            'name' => $nombre,
            'email' => fake()->unique()->safeEmail(),
            'cod_est' => $codEst,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make($codEst ?: $phone),
            'password_changed' => fake()->boolean(20),
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement(['estudiante', 'voluntario', 'admin']),
            'phone' => $phone,
            'bio' => fake()->randomElement($bios),
            'points' => fake()->numberBetween(0, 1000),
            'profile_photo_path' => null,
            'is_active' => fake()->boolean(90),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
