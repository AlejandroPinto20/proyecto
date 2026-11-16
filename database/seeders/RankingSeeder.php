<?php

namespace Database\Seeders;

use App\Models\Ranking;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankingSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        // Create rankings for all users
        foreach ($users as $user) {
            Ranking::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // Update all rankings with correct stats
        Ranking::updateAllRankings();
    }
}
