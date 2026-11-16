<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MarkovRecommendationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateMarkovRecommendations extends Command
{
    protected $signature = 'markov:update {--user-id= : Update recommendations for specific user}';
    protected $description = 'Update Markov chain recommendations for users';

    public function handle(MarkovRecommendationService $service)
    {
        $this->info('Updating Markov recommendations...');

        if ($userId = $this->option('user-id')) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
            
            $service->clearUserCache($user);
            $recommendations = $service->getRecommendationsForUser($user);
            $this->info("Updated recommendations for user: {$user->name} ({$recommendations->count()} recommendations)");
        } else {
            // Clear all recommendation caches
            $users = User::whereHas('activities')->get();
            $bar = $this->output->createProgressBar($users->count());
            
            foreach ($users as $user) {
                $service->clearUserCache($user);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info("Cleared recommendation cache for {$users->count()} users.");
        }

        return 0;
    }
}