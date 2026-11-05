<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

/**
 * Cambio de contraseña desde Mi cuenta (#17): pide la actual y aplica las
 * mismas reglas que el registro.
 */
class UpdateUserPassword implements UpdatesUserPasswords
{
    /**
     * @param  User  $user
     * @param  array<string, mixed>  $input
     */
    public function update($user, array $input): void
    {
        /** @var array{password: string} $data */
        $data = Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ])->validateWithBag('updatePassword');

        $user->forceFill(['password' => $data['password']])->save();
    }
}
