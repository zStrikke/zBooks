<?php

namespace App\Http\Controllers;

use App\Actions\Users\UpdateProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mi cuenta (#17): perfil (avatar y bio). La contraseña la cambia Fortify
 * (PUT /user/password, App\Actions\Fortify\UpdateUserPassword).
 */
class AccountController extends Controller
{
    public const PROFILE_UPDATED = 'profile-updated';

    public const AVATAR_REMOVED = 'avatar-removed';

    public function show(Request $request): View
    {
        return view('account.show', ['user' => $this->user($request)]);
    }

    public function updateProfile(Request $request, UpdateProfile $updater): RedirectResponse
    {
        $updater->update($this->user($request), $request->only(['bio', 'avatar']));

        return to_route('account')->with('status', self::PROFILE_UPDATED);
    }

    public function destroyAvatar(Request $request, UpdateProfile $updater): RedirectResponse
    {
        $updater->removeAvatar($this->user($request));

        return to_route('account')->with('status', self::AVATAR_REMOVED);
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
