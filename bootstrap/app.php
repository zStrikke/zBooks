<?php

use App\Http\Middleware\SetWebLocale;
use App\Http\ProblemDetails\ProblemDetailsRenderer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Aplica el limitador «api» (AppServiceProvider) a todas las rutas de la API
        $middleware->throttleApi();

        // La web en el idioma del usuario; la API, siempre en inglés (ADR-0011)
        $middleware->web(append: SetWebLocale::class);

        // Sin sesión, a crear la cuenta; con la #11 pasará a la pantalla de login
        $middleware->redirectGuestsTo(fn () => route('register'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Errores de la API en formato Problem Details (RFC 9457, ADR-0007)
        $exceptions->render(new ProblemDetailsRenderer);
    })->create();
