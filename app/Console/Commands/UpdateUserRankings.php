<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Ranking;
use Illuminate\Console\Command;

class UpdateUserRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rankings:update-all {--force : Force update even if ranking exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update rankings for all users to ensure achievements are properly calculated';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user rankings...');
        
        $users = User::all();
        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();
        
        $updated = 0;
        $created = 0;
        
        foreach ($users as $user) {
            $ranking = $user->ranking;
            
            if (!$ranking) {
                // Crear nuevo ranking
                $ranking = Ranking::create(['user_id' => $user->id]);
                $created++;
            } elseif ($this->option('force')) {
                $updated++;
            } else {
                // Solo actualizar si no existe
                if (!$ranking->exists) {
                    $updated++;
                }
            }
            
            // Actualizar estadísticas
            $ranking->updateStats();
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Rankings updated successfully!");
        $this->info("Created: {$created} new rankings");
        $this->info("Updated: {$updated} existing rankings");
        
        // Actualizar posiciones
        $this->info('Updating ranking positions...');
        Ranking::getTopRankings(100); // Actualizar top 100 posiciones
        
        $this->info('All rankings updated successfully!');
    }
}