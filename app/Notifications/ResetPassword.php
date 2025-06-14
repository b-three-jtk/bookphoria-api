<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    public function toMail($notifiable)
    {
        $url = config('app.frontend_url') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->view('emails.reset-password', ['url' => $url, 'userName' => $notifiable->name, 'count' => config('auth.passwords.users.expire')])
            ->subject('Reset Password Bookphoria')
            ->from(config('mail.from.address'), config('mail.from.name'));
    }
}