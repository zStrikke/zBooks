<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Users\ProfileStats;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Perfil público de un miembro (#16).
 */
class ProfileController extends Controller
{
    /**
     * /u/{username} sin distinguir mayúsculas: cualquier otra forma redirige (301)
     * a la original, para que cada perfil tenga una sola URL.
     */
    public function show(string $username): View|RedirectResponse
    {
        $user = User::whereRaw('lower(username) = ?', [mb_strtolower($username)])->firstOrFail();

        if ($user->username !== $username) {
            return redirect()->route('profile.show', $user->username, 301);
        }

        return view('profile.show', [
            'user' => $user,
            'stats' => ProfileStats::for($user),
        ]);
    }
}
