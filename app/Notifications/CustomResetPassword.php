<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    public function __construct(
        public string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

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
