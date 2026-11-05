<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Email para restablecer la contraseña (#11): en cola, con los textos de zBooks.
 * El enlace caduca en auth.passwords.users.expire minutos (60).
 */
class ResetPassword extends BaseResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Reset your zBooks password'))
            ->greeting(__('Hello, :username!', ['username' => $notifiable->username]))
            ->line(__('We received a request to reset the password of your account.'))
            ->action(__('Choose a new password'), $this->resetUrl($notifiable))
            ->line(__('The link expires in :minutes minutes.', [
                'minutes' => config('auth.passwords.users.expire', 60),
            ]))
            ->line(__('If you did not ask for it, you can ignore this email: your password does not change.'));
    }
}
