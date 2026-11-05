<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

/**
 * Tras registrarse (también sin JavaScript, por el POST de Fortify), al aviso de verificación.
 */
class RegisterResponse implements RegisterResponseContract
{
    /**
     * @param  Request  $request
     */
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 201);
        }

        // El aviso cuenta este primer email para el límite de reenvíos.
        $request->session()->put('auth.just_registered', true);

        return redirect()->route('verification.notice');
    }
}
