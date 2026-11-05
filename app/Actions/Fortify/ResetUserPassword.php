<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

/**
 * Nueva contraseña desde el enlace del email (#11). Mismas reglas que en el registro.
 */
class ResetUserPassword implements ResetsUserPasswords
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function reset(User $user, array $input): void
    {
        /** @var array{password: string} $data */
        $data = Validator::make($input, [
            'password' => ['required', 'string', Password::defaults()],
        ])->validate();

        $user->forceFill(['password' => $data['password']])->save();
    }
}
