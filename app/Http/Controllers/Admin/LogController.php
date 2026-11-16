<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $activityLogs = collect([
            (object)[
                'user' => (object)['name' => 'Juan Pérez'],
                'action' => 'Se unió a la actividad "Limpieza de Parque"',
                'created_at' => now()->subMinutes(5)
            ],
            (object)[
                'user' => (object)['name' => 'María García'],
                'action' => 'Completó la actividad "Ayuda Alimentaria"',
                'created_at' => now()->subMinutes(15)
            ],
            (object)[
                'user' => (object)['name' => 'Carlos López'],
                'action' => 'Descargó certificado #CERT-2024-001',
                'created_at' => now()->subHour()
            ]
        ]);

        $systemLogs = collect([
            (object)[
                'event' => 'Backup Automático',
                'description' => 'Backup de base de datos completado exitosamente',
                'created_at' => now()->subMinutes(30)
            ],
            (object)[
                'event' => 'Limpieza de Cache',
                'description' => 'Cache del sistema limpiado automáticamente',
                'created_at' => now()->subHour()
            ]
        ]);

        $errorLogs = collect([
            (object)[
                'level' => 'WARNING',
                'message' => 'Intento de acceso no autorizado',
                'context' => 'IP: 192.168.1.100 intentó acceder a /admin sin permisos',
                'created_at' => now()->subMinutes(45)
            ],
            (object)[
                'level' => 'ERROR',
                'message' => 'Error de conexión a base de datos',
                'context' => 'Timeout en consulta de certificados',
                'created_at' => now()->subHours(2)
            ]
        ]);

        return view('admin.logs', compact('activityLogs', 'systemLogs', 'errorLogs'));
    }
}