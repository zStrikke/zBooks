<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Throwable;

/**
 * Estado del servicio
 *
 * Comprueba que la API responde y que llega a la base de datos y a Redis.
 * Lo usan la monitorización y la app móvil antes de empezar a trabajar.
 */
class HealthController extends Controller
{
    /**
     * Comprobar el estado de la API
     *
     * Devuelve 200 si las dependencias responden y 503 si falla alguna.
     *
     * @unauthenticated
     *
     * @response array{
     *     data: array{
     *         status: 'ok'|'degraded',
     *         version: string,
     *         checks: array{database: 'ok'|'fail', redis: 'ok'|'fail'},
     *     },
     * }
     */
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->check(fn () => DB::select('select 1')),
            'redis' => $this->check(fn () => Redis::connection()->ping()),
        ];

        $ok = ! in_array('fail', $checks, true);

        return response()->json([
            'data' => [
                'status' => $ok ? 'ok' : 'degraded',
                'version' => (string) config('app.version'),
                'checks' => $checks,
            ],
        ], $ok ? 200 : 503);
    }

    /**
     * @param  callable(): mixed  $probe
     * @return 'ok'|'fail'
     */
    private function check(callable $probe): string
    {
        try {
            $probe();

            return 'ok';
        } catch (Throwable) {
            return 'fail';
        }
    }
}
