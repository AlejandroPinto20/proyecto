<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethod = fake()->randomElement(['qr_code', 'bank_transfer']);

        $donaciones = [
            ['titulo' => 'Donación para Campaña de Salud', 'desc' => 'Aporte económico destinado a la compra de medicamentos y material médico para jornadas de salud comunitaria.'],
            ['titulo' => 'Apoyo a Educación Rural', 'desc' => 'Contribución para la adquisición de útiles escolares y material didáctico para escuelas rurales.'],
            ['titulo' => 'Donación para Reforestación', 'desc' => 'Fondos destinados a la compra de plantines y herramientas para proyectos de reforestación urbana.'],
            ['titulo' => 'Ayuda para Familias Vulnerables', 'desc' => 'Donativo para la compra de alimentos y artículos de primera necesidad para familias de bajos recursos.'],
            ['titulo' => 'Apoyo a Adultos Mayores', 'desc' => 'Contribución para mejorar las condiciones de vida de personas de la tercera edad en situación de vulnerabilidad.'],
            ['titulo' => 'Donación para Infraestructura Comunitaria', 'desc' => 'Aporte para la construcción y mejora de espacios comunitarios en barrios periféricos.'],
            ['titulo' => 'Fondo para Becas Estudiantiles', 'desc' => 'Donación destinada a financiar becas de estudio para estudiantes de escasos recursos.'],
            ['titulo' => 'Apoyo a Proyectos Ambientales', 'desc' => 'Contribución para iniciativas de conservación ambiental y desarrollo sostenible.'],
        ];

        $notas = [
            'Donación realizada con mucho gusto.',
            'Espero que este aporte ayude a la comunidad.',
            'Feliz de poder contribuir a esta causa.',
            'Donación en apoyo al voluntariado universitario.',
            'Aporte para el desarrollo social de nuestra ciudad.',
        ];

        $donacion = fake()->randomElement($donaciones);
        $amount = fake()->randomFloat(2, 10, 1000);

        return [
            'user_id' => User::factory(),
            'title' => $donacion['titulo'],
            'description' => $donacion['desc'],
            'amount' => $amount,
            'type' => 'money',
            'status' => fake()->randomElement(['pending', 'approved', 'completed']),
            'donation_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'notes' => fake()->randomElement($notas),
            'payment_method' => $paymentMethod,
            'qr_code_data' => $paymentMethod === 'qr_code' ? json_encode([
                'amount' => $amount,
                'description' => $donacion['titulo'],
                'recipient' => 'Fundación UNIFRANZ',
                'reference' => 'DON-' . fake()->numberBetween(100000, 999999) . '-' . fake()->numberBetween(1, 100),
            ]) : null,
            'bank_account_info' => $paymentMethod === 'bank_transfer' ? 'Cuenta Corriente: 1234567890 - Banco Unión - Fundación UNIFRANZ' : null,
        ];
    }
}
