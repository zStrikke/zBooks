<?php

namespace App\Livewire\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Rules\Username;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Pantalla de registro (#10). Valida y crea la cuenta con la misma acción que
 * el POST /register de Fortify, inicia la sesión y lleva al aviso de verificación.
 */
class Register extends Component
{
    public string $username = '';

    public string $email = '';

    public string $password = '';

    public bool $privacy_policy = false;

    public function register(CreateNewUser $creator): void
    {
        $user = $creator->create([
            'username' => trim($this->username),
            'email' => $this->email,
            'password' => $this->password,
            'privacy_policy' => $this->privacy_policy,
        ]);

        event(new Registered($user));

        Auth::login($user);
        session()->regenerate();
        session()->put('auth.just_registered', true);

        $this->redirectRoute('verification.notice', navigate: false);
    }

    /**
     * Valida el campo al salir de él, antes de enviar el formulario.
     */
    public function updated(string $property): void
    {
        if ($property === 'username') {
            $this->validateOnly($property, ['username' => Username::rules()]);
        }
    }

    public function render(): View
    {
        return view('livewire.auth.register', [
            'min' => Username::MIN,
            'max' => Username::MAX,
        ])->title(__('Create your account'));
    }
}
