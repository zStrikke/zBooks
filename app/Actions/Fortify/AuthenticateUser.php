<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;

/**
 * Login con email o nombre de usuario (#11). Si lo escrito lleva @, se busca
 * por email; si no, por username sin distinguir mayúsculas (como su índice único).
 */
class AuthenticateUser
{
    public function __invoke(Request $request): ?User
    {
        $user = $this->find(mb_strtolower(trim((string) $request->input(Fortify::username()))));
        $password = (string) $request->input('password');

        return $user !== null && Hash::check($password, $user->password) ? $user : null;
    }

    private function find(string $login): ?User
    {
        if ($login === '') {
            return null;
        }

        return str_contains($login, '@')
            ? User::where('email', $login)->first()
            : User::whereRaw('lower(username) = ?', [$login])->first();
    }
}
