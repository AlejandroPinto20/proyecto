<?php

namespace Database\Seeders;

use App\Models\AccessLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AccessLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $actions = ['login', 'logout', 'admin_access', 'user_management', 'activity_management', 'donation_management', 'certificate_management'];
        $ips = ['192.168.1.100', '10.0.0.50', '172.16.0.25', '192.168.0.200', '203.0.113.45'];
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/119.0.0.0'
        ];

        for ($i = 0; $i < 150; $i++) {
            $user = $users->random();
            $action = $actions[array_rand($actions)];
            $success = $action === 'failed_login' ? false : (rand(1, 100) > 5);
            
            AccessLog::create([
                'user_id' => $success ? $user->id : ($action === 'failed_login' ? null : $user->id),
                'email' => $user->email,
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'action' => $action === 'failed_login' ? 'failed_login' : $action,
                'route' => $action === 'login' ? 'login' : 'admin.' . str_replace('_', '.', $action),
                'request_data' => ['email' => $user->email],
                'success' => $success,
                'session_id' => 'sess_' . uniqid(),
                'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
            ]);
        }

        // Agregar algunos intentos fallidos específicos
        for ($i = 0; $i < 20; $i++) {
            AccessLog::create([
                'user_id' => null,
                'email' => 'hacker' . rand(1, 100) . '@test.com',
                'ip_address' => $ips[array_rand($ips)],
                'user_agent' => $userAgents[array_rand($userAgents)],
                'action' => 'failed_login',
                'route' => 'login',
                'request_data' => ['email' => 'hacker' . rand(1, 100) . '@test.com'],
                'success' => false,
                'session_id' => 'sess_' . uniqid(),
                'created_at' => Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23))
            ]);
        }
    }
}