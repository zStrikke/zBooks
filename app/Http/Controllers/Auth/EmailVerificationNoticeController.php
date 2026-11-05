<?php

namespace App\Http\Controllers\Auth;

use App\Auth\VerificationEmailThrottle;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Aviso «Revisa tu correo» (#10, #69).
 */
class EmailVerificationNoticeController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        // El email del registro cuenta como el primer envío
        if ($request->session()->pull('auth.just_registered', false)) {
            VerificationEmailThrottle::hit($user);
        }

        return view('auth.verify-email', [
            'email' => $user->email,
            'expires' => (int) config('auth.verification.expire', 60),
            'wait' => VerificationEmailThrottle::secondsLeft($user),
        ]);
    }
}
