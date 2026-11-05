<?php

namespace App\Geo;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Buscador de direcciones con Nominatim (OpenStreetMap), siempre desde el
 * servidor para cumplir su política de uso: un User-Agent que nos identifica,
 * como mucho una petición por segundo entre todos y respuestas en caché.
 * https://operations.osmfoundation.org/policies/nominatim/
 */
class Nominatim
{
    private const LIMITER = 'nominatim';

    private const CACHE_DAYS = 7;

    private const MAX_RESULTS = 5;

    /**
     * @return list<array{name: string, latitude: float, longitude: float}>|null null si hay que esperar
     */
    public function search(string $query, string $locale): ?array
    {
        $query = Str::of($query)->squish()->lower()->limit(120, '')->toString();
        $clave = 'nominatim:'.$locale.':'.hash('sha256', $query);

        /** @var list<array{name: string, latitude: float, longitude: float}>|null $enCache */
        $enCache = Cache::get($clave);
        if ($enCache !== null) {
            return $enCache;
        }

        if (RateLimiter::tooManyAttempts(self::LIMITER, 1)) {
            return null;
        }
        RateLimiter::hit(self::LIMITER, 1);

        $resultados = $this->request($query, $locale);
        Cache::put($clave, $resultados, now()->addDays(self::CACHE_DAYS));

        return $resultados;
    }

    /**
     * @return list<array{name: string, latitude: float, longitude: float}>
     */
    private function request(string $query, string $locale): array
    {
        $respuesta = Http::withUserAgent((string) config('zbooks.nominatim.user_agent'))
            ->timeout(5)
            ->get((string) config('zbooks.nominatim.url'), [
                'q' => $query,
                'format' => 'jsonv2',
                'limit' => self::MAX_RESULTS,
                'accept-language' => $locale,
            ]);

        if (! $respuesta->successful() || ! is_array($respuesta->json())) {
            return [];
        }

        /** @var list<array{display_name: string, lat: string, lon: string}> $lugares */
        $lugares = $respuesta->json();

        return array_map(fn (array $lugar): array => [
            'name' => $lugar['display_name'],
            'latitude' => (float) $lugar['lat'],
            'longitude' => (float) $lugar['lon'],
        ], $lugares);
    }
}
