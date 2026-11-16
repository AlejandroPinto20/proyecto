<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ranking>
 */
class RankingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalPoints = fake()->numberBetween(0, 10000);

        return [
            'user_id' => User::factory(),
            'total_points' => $totalPoints,
            'activities_completed' => fake()->numberBetween(0, 50),
            'hours_volunteered' => fake()->numberBetween(0, 500),
            'donations_made' => fake()->numberBetween(0, 20),
            'certificates_earned' => fake()->numberBetween(0, 10),
            'rank' => $this->calculateRank($totalPoints),
            'rank_position' => null, // Will be updated later
        ];
    }

    private function calculateRank(int $points): string
    {
        if ($points >= 5000) {
            return 'Diamante';
        } elseif ($points >= 2500) {
            return 'Platino';
        } elseif ($points >= 1000) {
            return 'Oro';
        } elseif ($points >= 500) {
            return 'Plata';
        } else {
            return 'Bronce';
        }
    }
}
