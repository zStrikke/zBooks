<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

/**
 * Aviso «Revisa tu correo» (#10): explica qué falta y permite reenviar el
 * email, como mucho una vez por minuto.
 */
class VerifyEmail extends Component
{
    public const RESEND_EVERY_SECONDS = 60;

    /** Segundos hasta poder reenviar; 0 si ya se puede. */
    public int $wait = 0;

    public bool $sent = false;

    public function mount(): void
    {
        if ($this->user()->hasVerifiedEmail()) {
            $this->redirectRoute('home', navigate: false);

            return;
        }

        // El registro acaba de enviar el primer email: cuenta como un envío.
        if (session()->pull('auth.just_registered', false)) {
            RateLimiter::hit($this->key(), self::RESEND_EVERY_SECONDS);
        }

        $this->wait = RateLimiter::availableIn($this->key());
    }

    public function resend(): void
    {
        if ($this->user()->hasVerifiedEmail()) {
            $this->redirectRoute('home', navigate: false);

            return;
        }

        if (RateLimiter::tooManyAttempts($this->key(), 1)) {
            $this->wait = RateLimiter::availableIn($this->key());

            return;
        }

        RateLimiter::hit($this->key(), self::RESEND_EVERY_SECONDS);
        $this->user()->sendEmailVerificationNotification();

        $this->sent = true;
        $this->wait = self::RESEND_EVERY_SECONDS;
    }

    public function render(): View
    {
        return view('livewire.auth.verify-email', [
            'email' => $this->user()->email,
            'expires' => (int) config('auth.verification.expire', 60),
        ])->title(__('Check your email'));
    }

    private function user(): User
    {
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        return $user;
    }

    private function key(): string
    {
        return 'verify-email:'.$this->user()->getKey();
    }
}
