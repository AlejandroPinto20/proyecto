<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        \App\Models\User::factory()->create([
            'name' => 'Administrador UNIFRANZ',
            'email' => 'admin@unifranz.edu.bo',
            'role' => 'admin',
            'cod_est' => 'ADM001',
            'password_changed' => true,
        ]);

        // Test students
        \App\Models\User::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@est.unifranz.edu.bo',
            'role' => 'estudiante',
            'cod_est' => 'EST001',
            'password_changed' => false,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'María García',
            'email' => 'maria.garcia@est.unifranz.edu.bo',
            'role' => 'estudiante', 
            'cod_est' => 'EST002',
            'password_changed' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Carlos López',
            'email' => 'carlos.lopez@est.unifranz.edu.bo',
            'role' => 'estudiante',
            'cod_est' => 'EST003',
            'password_changed' => true,
        ]);

        // Additional random users
        \App\Models\User::factory(15)->create();
    }
}
