<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'certificate_number' => 'CERT-' . date('Y') . '-' . strtoupper(fake()->unique()->lexify('????????')),
            'total_hours' => fake()->numberBetween(10, 200),
            'hours_volunteered' => fake()->numberBetween(5, 100),
            'issue_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['active', 'revoked']),
        ];
    }
}
