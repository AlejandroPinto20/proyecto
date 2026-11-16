<?php

namespace App\Console\Commands;

use App\Models\Ranking;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rankings:update {--force : Force update all rankings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all user rankings based on their activities, donations, and certificates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating rankings...');
        
        $users = User::where('role', '!=', 'admin')
                    ->where('is_active', true)
                    ->get();

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $updated = 0;
        foreach ($users as $user) {
            $ranking = $user->ranking ?? Ranking::create(['user_id' => $user->id]);
            
            $oldPoints = $ranking->total_points;
            $ranking->updateStats();
            
            if ($oldPoints !== $ranking->total_points || $this->option('force')) {
                $updated++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Update positions
        Ranking::getTopRankings(100);
        
        $this->info("Rankings updated successfully! {$updated} rankings were modified.");
        
        // Show top 5
        $this->info('Top 5 Rankings:');
        $topRankings = Ranking::getTopRankings(5);
        
        foreach ($topRankings as $ranking) {
            $this->line("#{$ranking->rank_position} {$ranking->user->name} - {$ranking->total_points} points ({$ranking->rank})");
        }
    }
}