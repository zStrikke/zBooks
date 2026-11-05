<?php

namespace App\Geo;

/**
 * Redondea un punto a una rejilla de ~500 m (RNF-04, ADR-0013): la zona de un
 * miembro nunca guarda su dirección exacta.
 *
 * La rejilla va en metros, no en grados: un grado de longitud mide 111 km en
 * el ecuador y unos 84 km en Barcelona. El mismo cálculo está en
 * resources/js/zona.js para enseñar en el mapa el punto que se guardará.
 */
class ApproximateLocation
{
    public const GRID_METERS = 500;

    private const METERS_PER_DEGREE = 111_320;

    /**
     * @return array{float, float} [latitud, longitud]
     */
    public static function round(float $latitude, float $longitude): array
    {
        $pasoLat = self::GRID_METERS / self::METERS_PER_DEGREE;
        $lat = round($latitude / $pasoLat) * $pasoLat;

        // Cerca de los polos el coseno tiende a 0: se limita para no dividir por casi nada
        $pasoLon = $pasoLat / max(cos(deg2rad($lat)), 0.01);
        $lon = round($longitude / $pasoLon) * $pasoLon;

        return [round($lat, 6), round(self::wrap($lon), 6)];
    }

    private static function wrap(float $longitude): float
    {
        return $longitude > 180 ? $longitude - 360 : max($longitude, -180);
    }
}
