<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
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
                    ->subject('¡Bienvenido a UNIFRANZ - Sistema de Voluntariado!')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('¡Bienvenido a nuestra plataforma de voluntariado estudiantil!')
                    ->line('Tu cuenta ha sido creada exitosamente. Aquí tienes la información importante para acceder:')
                    ->line('**Email:** ' . $notifiable->email)
                    ->line('**Contraseña temporal:** ' . $this->defaultPassword)
                    ->line('**Importante:** Por seguridad, te recomendamos cambiar tu contraseña lo antes posible.')
                    ->action('Iniciar Sesión', url('/login'))
                    ->action('Cambiar Contraseña', url('/profile'))
                    ->line('Como voluntario, podrás:')
                    ->line('• Participar en actividades de voluntariado')
                    ->line('• Ganar puntos y subir en rankings')
                    ->line('• Obtener certificados por tus horas de servicio')
                    ->line('• Realizar donaciones a causas sociales')
                    ->line('¡Esperamos verte pronto contribuyendo con la comunidad!')
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
            'message' => 'Bienvenido al sistema de voluntariado UNIFRANZ.',
            'default_password' => $this->defaultPassword,
            'login_url' => url('/login'),
            'profile_url' => url('/profile'),
        ];
    }
}
