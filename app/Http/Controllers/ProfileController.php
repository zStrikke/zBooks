<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Users\ProfileStats;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Perfil de un miembro (#16). No es público (#93): solo lo ve el propio
 * miembro y, con la #37, sus amigos (App\Policies\UserPolicy::viewProfile).
 */
class ProfileController extends Controller
{
    /**
     * /u/{username} sin distinguir mayúsculas: cualquier otra forma redirige (301)
     * a la original, para que cada perfil tenga una sola URL.
     *
     * Quien no puede verlo recibe un 404, como si no existiera, y la comprobación
     * va antes de la redirección: así no sirve para averiguar qué usernames existen.
     */
    public function show(Request $request, string $username): View|RedirectResponse
    {
        $user = User::whereRaw('lower(username) = ?', [mb_strtolower($username)])->first();

        abort_unless($user !== null && $request->user()?->can('viewProfile', $user), 404);

        if ($user->username !== $username) {
            return redirect()->route('profile.show', $user->username, 301);
        }

        return view('profile.show', [
            'user' => $user,
            'stats' => ProfileStats::for($user),
        ]);
    }
}
