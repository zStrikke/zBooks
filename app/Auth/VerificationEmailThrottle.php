<?php

namespace App\Auth;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Límite de envío del email de verificación (#10, #69): uno por minuto y usuario.
 *
 * Lo comparten tres sitios: el limitador «verification» que Fortify aplica al
 * POST de reenvío, el registro (el primer email cuenta) y el aviso, que muestra
 * cuántos segundos faltan. Por eso la clave se calcula aquí, igual que la calcula
 * el middleware de Laravel para un limitador con nombre: md5(nombre.clave).
 */
class VerificationEmailThrottle
{
    public const LIMITER = 'verification';

    public const SECONDS = 60;

    /**
     * Limitador «verification» de las rutas de Fortify. Reenviar: 1 por minuto; si se
     * adelanta, vuelve al aviso con la cuenta atrás en vez de una página 429.
     * Abrir el enlace del email: 6 por minuto.
     */
    public static function forRequest(Request $request): Limit
    {
        $user = $request->user();

        if (! $request->isMethod('post') || ! $user instanceof User) {
            return Limit::perMinute(6)->by('verify:'.($user?->getAuthIdentifier() ?? $request->ip()));
        }

        return Limit::perMinute(1)
            ->by(self::by($user))
            ->response(fn () => redirect()->route('verification.notice'));
    }

    public static function hit(User $user): void
    {
        RateLimiter::hit(self::key($user), self::SECONDS);
    }

    public static function secondsLeft(User $user): int
    {
        return RateLimiter::availableIn(self::key($user));
    }

    private static function by(User $user): string
    {
        return 'send:'.$user->getKey();
    }

    private static function key(User $user): string
    {
        // Mismo cálculo que ThrottleRequests (no es criptografía: solo nombra la clave)
        return hash('md5', self::LIMITER.self::by($user));
    }
}
