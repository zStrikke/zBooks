<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Idioma de la web (ADR-0011). De momento, siempre el de reserva (español).
 * La #53 añade el selector y usa users.locale, la cookie y Accept-Language.
 */
class SetWebLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale((string) config('app.web_fallback_locale', 'es'));

        return $next($request);
    }
}
