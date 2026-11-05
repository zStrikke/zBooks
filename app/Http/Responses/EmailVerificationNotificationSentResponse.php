<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse as SentResponseContract;

/**
 * Tras reenviar el email de verificación (#69): de vuelta al aviso con la confirmación.
 */
class EmailVerificationNotificationSentResponse implements SentResponseContract
{
    /**
     * @param  Request  $request
     */
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        return $request->wantsJson()
            ? new JsonResponse('', 202)
            : redirect()->route('verification.notice')->with('resent', true);
    }
}
