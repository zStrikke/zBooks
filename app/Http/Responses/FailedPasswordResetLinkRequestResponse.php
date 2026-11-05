<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FailedResetLinkContract;

/**
 * «He olvidado mi contraseña» que no envía el enlace (#11). Con un email sin
 * cuenta responde lo mismo que si existiera, para no revelar qué emails están registrados.
 */
class FailedPasswordResetLinkRequestResponse implements FailedResetLinkContract
{
    public function __construct(private readonly string $status) {}

    /**
     * @param  Request  $request
     */
    public function toResponse($request): RedirectResponse
    {
        if ($this->status === Password::INVALID_USER) {
            return back()->with('status', __('passwords.sent'));
        }

        if ($request->wantsJson()) {
            throw ValidationException::withMessages(['email' => [__($this->status)]]);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($this->status)]);
    }
}
