<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create various types of notifications
            $notifications = [
                [
                    'type' => 'App\\Notifications\\WelcomeNotification',
                    'data' => json_encode([
                        'type' => 'welcome',
                        'title' => 'Bienvenido al Sistema',
                        'message' => '¡Bienvenido al sistema de voluntariado UNIFRANZ! Completa tu perfil para comenzar.'
                    ])
                ],
                [
                    'type' => 'App\\Notifications\\NewActivityNotification',
                    'data' => json_encode([
                        'type' => 'activity',
                        'title' => 'Nueva Actividad Disponible',
                        'message' => 'Se ha publicado una nueva actividad de voluntariado: "Limpieza de Parques"'
                    ])
                ],
                [
                    'type' => 'App\\Notifications\\CertificateIssuedNotification',
                    'data' => json_encode([
                        'type' => 'certificate',
                        'title' => 'Certificado Emitido',
                        'message' => 'Tu certificado de 20 horas de voluntariado ha sido emitido exitosamente.'
                    ])
                ],
                [
                    'type' => 'App\\Notifications\\DonationApprovedNotification',
                    'data' => json_encode([
                        'type' => 'donation',
                        'title' => 'Donación Aprobada',
                        'message' => 'Tu donación de Bs. 100 ha sido aprobada y procesada.'
                    ])
                ]
            ];

            foreach ($notifications as $index => $notification) {
                DB::table('notifications')->insert([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => $notification['type'],
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => $notification['data'],
                    'read_at' => $index < 2 ? null : now(), // First 2 unread, rest read
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('Test notifications created successfully!');
    }
}