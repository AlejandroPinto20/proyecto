<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public static function create($userId, $type, $title, $message, $data = [])
    {
        DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\\Notifications\\SystemNotification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $userId,
            'data' => json_encode(array_merge([
                'type' => $type,
                'title' => $title,
                'message' => $message,
            ], $data)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function activityAccepted($userId, $activityName)
    {
        self::create(
            $userId,
            'activity_accepted',
            'Participación Aceptada',
            "Tu participación en la actividad '{$activityName}' ha sido aceptada."
        );
    }

    public static function activityJoined($userId, $activityName)
    {
        self::create(
            $userId,
            'activity_joined',
            'Te uniste a una actividad',
            "Te has unido exitosamente a la actividad '{$activityName}'."
        );
    }

    public static function activityRejected($userId, $activityName, $reason = null)
    {
        $message = "Tu participación en la actividad '{$activityName}' ha sido rechazada.";
        if ($reason) {
            $message .= " Motivo: {$reason}";
        }
        
        self::create(
            $userId,
            'activity_rejected',
            'Participación Rechazada',
            $message
        );
    }

    public static function certificateGenerated($userId, $certificateNumber)
    {
        self::create(
            $userId,
            'certificate',
            'Nuevo Certificado',
            "Se ha generado tu certificado #{$certificateNumber}. ¡Ya puedes descargarlo!"
        );
    }

    public static function activityReminder($userId, $activityName, $date)
    {
        self::create(
            $userId,
            'activity_reminder',
            'Recordatorio de Actividad',
            "Recordatorio: La actividad '{$activityName}' es el {$date}."
        );
    }
}