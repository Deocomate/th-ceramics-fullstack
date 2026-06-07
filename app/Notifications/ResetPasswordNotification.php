<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        $routeName = $notifiable->isAdmin()
            ? 'admin.auth.reset-password'
            : 'client.auth.reset-password';

        $url = route($routeName, [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Yêu cầu đặt lại mật khẩu - '.config('app.name'))
            ->markdown('components.emails.auth.reset_password', ['url' => $url])
            ->action('Đặt lại mật khẩu', $url);
    }
}
