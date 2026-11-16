<?php

namespace App\Notifications;

use App\Models\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationRejectedNotification extends Notification
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
            ->subject('Solicitud de Participación No Aprobada - UNIFRANZ')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Te informamos que tu solicitud para participar en la siguiente actividad no fue aprobada.')
            ->line('**Actividad:** ' . $this->activity->name)
            ->line('No te desanimes, hay muchas otras oportunidades de voluntariado disponibles.')
            ->action('Ver Otras Actividades', url('/calendar'))
            ->line('¡Gracias por tu interés en participar!')
            ->salutation('Saludos cordiales, Equipo UNIFRANZ');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'activity',
            'title' => 'Participación No Aprobada',
            'message' => "Tu solicitud para participar en '{$this->activity->name}' no fue aprobada.",
            'activity_id' => $this->activity->id,
            'activity_name' => $this->activity->name,
        ];
    }
}