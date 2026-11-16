<?php

namespace App\Notifications;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewParticipationRequestNotification extends Notification
{
    use Queueable;

    protected $activity;
    protected $user;

    public function __construct(Activity $activity, User $user)
    {
        $this->activity = $activity;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Nueva Solicitud de Participación - UNIFRANZ')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tienes una nueva solicitud de participación pendiente de revisión.')
            ->line('**Usuario:** ' . $this->user->name)
            ->line('**Email:** ' . $this->user->email)
            ->line('**Actividad:** ' . $this->activity->name)
            ->action('Revisar Solicitudes', url('/admin/activities/pending-participations'))
            ->line('Por favor, revisa y aprueba o rechaza la solicitud lo antes posible.')
            ->salutation('Saludos cordiales, Sistema UNIFRANZ');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'activity',
            'title' => 'Nueva Solicitud de Participación',
            'message' => "{$this->user->name} solicita participar en '{$this->activity->name}'.",
            'activity_id' => $this->activity->id,
            'activity_name' => $this->activity->name,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
        ];
    }
}