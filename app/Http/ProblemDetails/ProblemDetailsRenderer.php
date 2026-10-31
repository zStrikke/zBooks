<?php

namespace App\Http\ProblemDetails;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Convierte cualquier excepción de la API en una respuesta RFC 9457
 * («Problem Details for HTTP APIs»), con `Content-Type: application/problem+json`.
 *
 * Campos: `type`, `title`, `status`, `detail` e `instance`. Los errores de
 * validación añaden la extensión `errors`, con los mensajes por campo.
 *
 * @see https://www.rfc-editor.org/rfc/rfc9457
 */
class ProblemDetailsRenderer
{
    public const CONTENT_TYPE = 'application/problem+json';

    /**
     * Solo actúa en rutas de la API; la web sigue con las páginas de error normales.
     */
    public function __invoke(Throwable $e, Request $request): ?JsonResponse
    {
        if (! $request->is('api/*')) {
            return null;
        }

        return match (true) {
            $e instanceof ValidationException => $this->validation($e, $request),
            $e instanceof AuthenticationException => $this->unauthenticated($request),
            $e instanceof HttpExceptionInterface => $this->http($e, $request),
            default => $this->serverError($e, $request),
        };
    }

    private function validation(ValidationException $e, Request $request): JsonResponse
    {
        return $this->problem(
            $request,
            status: $e->status,
            detail: __('Revisa los campos marcados en «errors».'),
            type: url('/problems/validation-error'),
            title: __('Los datos enviados no son válidos.'),
            extensions: ['errors' => $e->errors()],
        );
    }

    private function unauthenticated(Request $request): JsonResponse
    {
        return $this->problem($request, 401, __('Necesitas iniciar sesión para acceder a este recurso.'));
    }

    private function http(HttpExceptionInterface $e, Request $request): JsonResponse
    {
        $status = $e->getStatusCode();

        $detail = match ($status) {
            403 => __('No tienes permiso para realizar esta acción.'),
            404 => __('El recurso solicitado no existe.'),
            405 => __('El método HTTP no está permitido en esta ruta.'),
            429 => __('Has hecho demasiadas peticiones. Espera antes de volver a intentarlo.'),
            default => $e->getMessage() !== '' ? $e->getMessage() : null,
        };

        // Las cabeceras de la excepción (p. ej. Retry-After en un 429) se conservan
        return $this->problem($request, $status, $detail, headers: $e->getHeaders());
    }

    private function serverError(Throwable $e, Request $request): JsonResponse
    {
        // El mensaje real solo se muestra en local; en producción podría filtrar datos internos
        $detail = config('app.debug') ? $e->getMessage() : __('Se ha producido un error inesperado.');

        return $this->problem($request, 500, $detail);
    }

    /**
     * @param  array<string, mixed>  $extensions
     * @param  array<string, string>  $headers
     */
    private function problem(
        Request $request,
        int $status,
        ?string $detail = null,
        string $type = 'about:blank',
        ?string $title = null,
        array $extensions = [],
        array $headers = [],
    ): JsonResponse {
        $body = array_filter([
            'type' => $type,
            // Con type «about:blank», el título es la frase estándar del código HTTP (RFC 9457, 4.2.1)
            'title' => $title ?? (Response::$statusTexts[$status] ?? 'Error'),
            'status' => $status,
            'detail' => $detail,
            'instance' => '/'.ltrim($request->path(), '/'),
        ], fn ($value) => $value !== null) + $extensions;

        return new JsonResponse($body, $status, ['Content-Type' => self::CONTENT_TYPE] + $headers);
    }
}
