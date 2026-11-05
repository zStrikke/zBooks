<?php

namespace App\Actions\Fortify;

use App\Enums\Role;
use App\Legal\PrivacyPolicy;
use App\Models\User;
use App\Rules\Username;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Alta de usuarios (#10). La usan la web (Fortify y Livewire) y, más adelante,
 * la API (#12): una sola lógica para los dos accesos (ADR-0012).
 */
class CreateNewUser implements CreatesNewUsers
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        // El email se compara y se guarda en minúsculas: A@x.com y a@x.com son la misma cuenta.
        if (is_string($input['email'] ?? null)) {
            $input['email'] = mb_strtolower(trim($input['email']));
        }

        /** @var array{username: string, email: string, password: string} $data */
        $data = Validator::make($input, self::rules())->validate();

        return DB::transaction(function () use ($data): User {
            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            PrivacyPolicy::recordAcceptance($user);
            $user->assignRole(Role::Member);

            return $user;
        });
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'username' => Username::rules(),
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            ...PrivacyPolicy::rules(),
        ];
    }
}
