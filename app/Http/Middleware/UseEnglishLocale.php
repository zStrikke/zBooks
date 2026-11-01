<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * La API responde siempre en inglés (ADR-0011), sin importar `APP_LOCALE`
 * ni la cabecera `Accept-Language`. Así el contrato no cambia según el cliente.
 */
class UseEnglishLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale('en');

        return $next($request);
    }
}
