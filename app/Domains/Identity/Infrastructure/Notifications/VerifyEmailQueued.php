<?php

namespace App\Domains\Identity\Infrastructure\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Xác thực email - '.config('app.name'))
            ->markdown('components.emails.identity.auth.verify_email', [
                'url' => $url,
                'user' => $notifiable,
            ])
            ->action('Xác thực email', $url);
    }
}
