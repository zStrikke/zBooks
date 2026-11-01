<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
| Contrato del formato de error de la API (RFC 9457).
| Las rutas /api/v1/_test/* solo existen durante estos tests.
*/

beforeEach(function () {
    Route::middleware('api')->prefix('api/v1/_test')->group(function () {
        Route::post('validation', fn (Request $request) => $request->validate([
            'title' => ['required', 'string'],
            'isbn' => ['required', 'digits:13'],
        ]));
        Route::get('unauthenticated', fn () => throw new AuthenticationException);
        Route::get('forbidden', fn () => throw new AuthorizationException);
        Route::get('broken', fn () => throw new RuntimeException('Database password: s3cret'));
    });
});

it('responde un 404 en formato Problem Details', function () {
    $this->getJson('/api/v1/no-existe')
        ->assertNotFound()
        ->assertHeader('Content-Type', 'application/problem+json')
        ->assertExactJson([
            'type' => 'about:blank',
            'title' => 'Not Found',
            'status' => 404,
            'detail' => 'The requested resource does not exist.',
            'instance' => '/api/v1/no-existe',
        ]);
});

it('usa Problem Details aunque el cliente no pida JSON', function () {
    $this->get('/api/v1/no-existe')
        ->assertNotFound()
        ->assertHeader('Content-Type', 'application/problem+json');
});

it('devuelve los errores de validación por campo', function () {
    $this->postJson('/api/v1/_test/validation', ['isbn' => '123'])
        ->assertUnprocessable()
        ->assertHeader('Content-Type', 'application/problem+json')
        ->assertJsonPath('type', url('/problems/validation-error'))
        ->assertJsonPath('status', 422)
        ->assertJsonValidationErrors(['title', 'isbn']);
});

it('responde 401, 403 y 405 con su código y título estándar', function (string $metodo, string $ruta, int $status, string $titulo) {
    $this->json($metodo, $ruta)
        ->assertStatus($status)
        ->assertHeader('Content-Type', 'application/problem+json')
        ->assertJsonPath('status', $status)
        ->assertJsonPath('title', $titulo)
        ->assertJsonStructure(['type', 'title', 'status', 'detail', 'instance']);
})->with([
    '401 sin sesión' => ['GET', '/api/v1/_test/unauthenticated', 401, 'Unauthorized'],
    '403 sin permiso' => ['GET', '/api/v1/_test/forbidden', 403, 'Forbidden'],
    '405 método no permitido' => ['DELETE', '/api/v1/health', 405, 'Method Not Allowed'],
]);

it('responde en inglés aunque el cliente pida español', function () {
    // Simula la traducción española que llegará con la web multiidioma (#43).
    // Falla si APP_LOCALE deja de ser «en» o si la API empieza a mirar Accept-Language.
    app('translator')->addLines(['validation.required' => 'El campo :attribute es obligatorio.'], 'es');

    $this->withHeader('Accept-Language', 'es')
        ->postJson('/api/v1/_test/validation', ['isbn' => '1234567890123'])
        ->assertUnprocessable()
        ->assertJsonPath('title', 'The given data was invalid.')
        ->assertJsonPath('errors.title.0', 'The title field is required.');
});

it('no filtra el mensaje de un error 500 en producción', function () {
    config(['app.debug' => false]);

    $this->getJson('/api/v1/_test/broken')
        ->assertStatus(500)
        ->assertHeader('Content-Type', 'application/problem+json')
        ->assertJsonPath('detail', 'An unexpected error occurred.')
        ->assertDontSee('s3cret');
});

it('limita la API a 60 peticiones por minuto y avisa cuándo reintentar', function () {
    foreach (range(1, 60) as $_) {
        $this->getJson('/api/v1/health')->assertOk();
    }

    $this->getJson('/api/v1/health')
        ->assertTooManyRequests()
        ->assertHeader('Content-Type', 'application/problem+json')
        ->assertHeader('Retry-After')
        ->assertJsonPath('status', 429);
});

it('no cambia las páginas de error de la web', function () {
    $this->get('/no-existe')
        ->assertNotFound()
        ->assertHeader('Content-Type', 'text/html; charset=utf-8');
});
