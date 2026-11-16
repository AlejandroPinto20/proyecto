<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Activity;
use App\Models\Donation;
use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsTestDataSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios de prueba
        $users = [
            ['name' => 'Ana García', 'email' => 'ana.garcia@unifranz.edu.bo', 'role' => 'voluntario'],
            ['name' => 'Carlos Mendoza', 'email' => 'carlos.mendoza@unifranz.edu.bo', 'role' => 'voluntario'],
            ['name' => 'María López', 'email' => 'maria.lopez@unifranz.edu.bo', 'role' => 'estudiante'],
            ['name' => 'Pedro Ramírez', 'email' => 'pedro.ramirez@unifranz.edu.bo', 'role' => 'admin'],
            ['name' => 'Laura Fernández', 'email' => 'laura.fernandez@unifranz.edu.bo', 'role' => 'voluntario'],
            ['name' => 'Diego Morales', 'email' => 'diego.morales@unifranz.edu.bo', 'role' => 'estudiante'],
            ['name' => 'Sofia Vargas', 'email' => 'sofia.vargas@unifranz.edu.bo', 'role' => 'voluntario'],
            ['name' => 'Roberto Silva', 'email' => 'roberto.silva@unifranz.edu.bo', 'role' => 'estudiante'],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'created_at' => Carbon::now()->subDays(rand(1, 365)),
                ]
            );
        }

        // Crear actividades de prueba
        $activities = [
            ['name' => 'Limpieza de Parques', 'description' => 'Actividad de limpieza en parques públicos', 'category' => 'medio_ambiente'],
            ['name' => 'Apoyo Educativo', 'description' => 'Tutorías para estudiantes de primaria', 'category' => 'educacion'],
            ['name' => 'Donación de Sangre', 'description' => 'Campaña de donación de sangre', 'category' => 'salud'],
            ['name' => 'Construcción de Viviendas', 'description' => 'Apoyo en construcción de viviendas sociales', 'category' => 'comunitario'],
            ['name' => 'Cuidado de Ancianos', 'description' => 'Acompañamiento a adultos mayores', 'category' => 'social'],
            ['name' => 'Reforestación', 'description' => 'Plantación de árboles en zonas urbanas', 'category' => 'medio_ambiente'],
            ['name' => 'Alfabetización Digital', 'description' => 'Enseñanza de computación básica', 'category' => 'educacion'],
            ['name' => 'Campaña de Vacunación', 'description' => 'Apoyo en campañas de vacunación', 'category' => 'salud'],
        ];

        $adminUser = User::where('role', 'admin')->first();
        
        foreach ($activities as $activityData) {
            $startDate = Carbon::now()->addDays(rand(1, 60));
            $activity = Activity::firstOrCreate(
                ['name' => $activityData['name']],
                [
                    'description' => $activityData['description'],
                    'category' => $activityData['category'],
                    'user_id' => $adminUser->id,
                    'start_date' => $startDate,
                    'end_date' => $startDate->copy()->addHours(rand(2, 8)),
                    'location' => 'La Paz, Bolivia',
                    'max_participants' => rand(10, 50),
                    'status' => 'active',
                    'registration_type' => 'open',
                    'created_at' => Carbon::now()->subDays(rand(1, 180)),
                ]
            );

            // Asignar participantes aleatorios
            $volunteers = User::whereIn('role', ['voluntario', 'estudiante'])->inRandomOrder()->take(rand(3, 8))->get();
            foreach ($volunteers as $volunteer) {
                DB::table('activity_user')->insertOrIgnore([
                    'activity_id' => $activity->id,
                    'user_id' => $volunteer->id,
                    'status' => collect(['pending', 'approved', 'completed'])->random(),
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Crear donaciones de prueba
        $donations = [
            ['title' => 'Donación para Limpieza', 'amount' => 150.00, 'status' => 'completed'],
            ['title' => 'Apoyo Educativo', 'amount' => 250.50, 'status' => 'completed'],
            ['title' => 'Donación General', 'amount' => 100.00, 'status' => 'pending'],
            ['title' => 'Construcción Social', 'amount' => 300.75, 'status' => 'completed'],
            ['title' => 'Cuidado de Ancianos', 'amount' => 75.25, 'status' => 'approved'],
            ['title' => 'Reforestación', 'amount' => 500.00, 'status' => 'completed'],
            ['title' => 'Alfabetización', 'amount' => 125.50, 'status' => 'completed'],
            ['title' => 'Campaña de Salud', 'amount' => 200.00, 'status' => 'pending'],
            ['title' => 'Donación Navideña', 'amount' => 350.25, 'status' => 'completed'],
            ['title' => 'Apoyo Comunitario', 'amount' => 180.75, 'status' => 'completed'],
            ['title' => 'Donación de Emergencia', 'amount' => 90.50, 'status' => 'approved'],
            ['title' => 'Fondo de Becas', 'amount' => 275.00, 'status' => 'completed'],
        ];

        $users = User::all();
        foreach ($donations as $donationData) {
            Donation::create([
                'user_id' => $users->random()->id,
                'title' => $donationData['title'],
                'amount' => $donationData['amount'],
                'status' => $donationData['status'],
                'payment_method' => collect(['bank_transfer', 'qr_code'])->random(),
                'description' => 'Donación para actividades de voluntariado UNIFRANZ',
                'type' => 'money',
                'donation_date' => Carbon::now()->subDays(rand(1, 365)),
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // Crear certificados de prueba
        $completedUsers = User::whereIn('role', ['voluntario', 'estudiante'])->take(5)->get();
        
        foreach ($completedUsers as $user) {
            Certificate::firstOrCreate([
                'user_id' => $user->id,
                'certificate_number' => Certificate::generateCertificateNumber(),
            ], [
                'total_hours' => rand(20, 100),
                'hours_volunteered' => rand(10, 80),
                'issue_date' => Carbon::now()->subDays(rand(1, 90)),
                'description' => "Certificado de voluntariado por {$user->name}",
                'status' => 'active',
                'created_at' => Carbon::now()->subDays(rand(1, 90)),
            ]);
        }

        $this->command->info('✅ Datos de prueba para reportes creados exitosamente');
        $this->command->info('📊 Estadísticas generadas:');
        $this->command->info('   - Usuarios: ' . User::count());
        $this->command->info('   - Actividades: ' . Activity::count());
        $this->command->info('   - Donaciones: ' . Donation::count());
        $this->command->info('   - Certificados: ' . Certificate::count());
        $this->command->info('   - Participaciones: ' . DB::table('activity_user')->count());
    }
}