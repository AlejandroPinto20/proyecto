<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // List of tables to clean
        $tables = [
            'notifications', 'activity_user', 'user_skills', 'skill_transitions',
            'certificates', 'donations', 'rankings', 'activities', 'skills',
            'users', 'sessions', 'cache', 'jobs'
        ];

        // Truncate tables if they exist
        foreach ($tables as $table) {
            try {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                }
            } catch (\Exception $e) {
                // Skip if table doesn't exist
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Database cleaned successfully!');
    }
}