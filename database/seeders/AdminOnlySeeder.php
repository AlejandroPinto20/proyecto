<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminOnlySeeder extends Seeder
{
    public function run(): void
    {
        // Crear solo el administrador principal
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@unifranz.edu.bo',
            'password' => Hash::make('admin123'),
            'cod_est' => 'ADMIN001',
            'role' => 'admin',
            'phone' => '+59178945612',
            'bio' => 'Administrador del sistema de voluntariado UNIFRANZ',
            'points' => 0,
            'is_active' => true,
            'skills_completed' => false,
            'password_changed' => true,
        ]);

        $this->command->info('✅ Administrador creado exitosamente!');
        $this->command->info('📧 Email: admin@unifranz.edu.bo');
        $this->command->info('🔑 Password: admin123');
    }
}
