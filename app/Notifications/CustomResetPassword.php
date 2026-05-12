<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificación personalizada para el restablecimiento de contraseña.
 *
 * Envía un correo con vista Blade propia en lugar de la plantilla por defecto de Laravel.
 */
class CustomResetPassword extends Notification
{
    use Queueable;

    /**
     * @param  string  $token  Token de restablecimiento generado por el broker.
     */
    public function __construct(
        public string $token
    ) {}

    /**
     * Canales de entrega de la notificación.
     *
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Construye el mensaje de correo con la URL de restablecimiento.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // URL estándar de Laravel/Fortify para reset:
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablece tu contraseña')
            // Usa tu vista Blade:
            ->view('auth.reset-password-mail', [
                'name' => $notifiable->name ?? null,
                'resetUrl' => $url,
                'email' => $notifiable->getEmailForPasswordReset(),
                'minutes' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60),
                'appName' => config('app.name'),
            ]);
    }
}
