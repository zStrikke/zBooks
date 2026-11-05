<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Email de verificación (#10): en cola, con enlace firmado que caduca en
 * auth.verification.expire minutos (60) y los textos de zBooks.
 */
class VerifyEmail extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(__('Activate your zBooks account'))
            ->greeting(__('Hello, :username!', ['username' => $notifiable->username]))
            ->line(__('Press the button to verify your email and start registering and releasing books.'))
            ->action(__('Verify my email'), $url)
            ->line(__('The link expires in :minutes minutes.', ['minutes' => config('auth.verification.expire', 60)]))
            ->line(__('If you did not create an account, you can ignore this email.'));
    }
}
