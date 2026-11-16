<?php

namespace App\Notifications;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationApprovedNotification extends Notification
{
    use Queueable;

    protected $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Participación Aprobada - UNIFRANZ')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('¡Excelente noticia! Tu solicitud para participar en una actividad ha sido aprobada.')
            ->line('**Actividad:** ' . $this->activity->name)
            ->line('**Descripción:** ' . $this->activity->description)
            ->action('Ver Actividad', url('/calendar'))
            ->line('¡Esperamos verte pronto en la actividad!')
            ->salutation('Saludos cordiales, Equipo UNIFRANZ');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'activity',
            'title' => '¡Participación Aprobada!',
            'message' => "Tu solicitud para participar en '{$this->activity->name}' ha sido aprobada.",
            'activity_id' => $this->activity->id,
            'activity_name' => $this->activity->name,
        ];
    }
}