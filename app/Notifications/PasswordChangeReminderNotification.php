<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangeReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $defaultPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $defaultPassword)
    {
        $this->defaultPassword = $defaultPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Recordatorio: Cambia tu Contraseña - UNIFRANZ')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Te recordamos que tu contraseña actual es temporal y por seguridad deberías cambiarla.')
                    ->line('**Tu contraseña actual:** ' . $this->defaultPassword)
                    ->line('Para mayor seguridad, te recomendamos establecer una contraseña personal.')
                    ->action('Cambiar Contraseña', url('/profile'))
                    ->line('Si ya cambiaste tu contraseña, puedes ignorar este mensaje.')
                    ->line('¡Gracias por ser parte de nuestra comunidad de voluntarios!')
                    ->salutation('Saludos cordiales, Equipo UNIFRANZ');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Recordatorio para cambiar tu contraseña por seguridad.',
            'default_password' => $this->defaultPassword,
            'action_url' => url('/profile'),
        ];
    }
}
