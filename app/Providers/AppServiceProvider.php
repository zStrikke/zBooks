<?php

namespace App\Providers;

use App\Auth\VerificationEmailThrottle;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Contraseñas (#10): mínimo 8 caracteres; en producción, además, que no
        // aparezcan en filtraciones conocidas (haveibeenpwned.com, sin enviar la contraseña).
        Password::defaults(fn () => app()->isProduction()
            ? Password::min(8)->uncompromised()
            : Password::min(8));

        // 60 peticiones por minuto: por usuario si ha iniciado sesión, si no por IP
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)
            ->by($request->user()?->getAuthIdentifier() ?? $request->ip()));

        // Verificación del email (#10, #69): reenviar, 1 por minuto; abrir el enlace, 6 por minuto
        RateLimiter::for(VerificationEmailThrottle::LIMITER, VerificationEmailThrottle::forRequest(...));
    }
}
