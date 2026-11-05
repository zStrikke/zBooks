<?php

use App\Geo\ApproximateLocation;

/*
| Redondeo de la zona a ~250 m (#18, RNF-04)
*/

function metrosEntre(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $r = 6_371_000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

    return 2 * $r * asin(sqrt($a));
}

it('mueve el punto, pero nunca más de media celda de la rejilla (~177 m)', function (float $lat, float $lon) {
    [$rLat, $rLon] = ApproximateLocation::round($lat, $lon);

    expect(metrosEntre($lat, $lon, $rLat, $rLon))->toBeLessThan(180);
})->with([
    'Gràcia' => [41.40312, 2.15667],
    'Madrid' => [40.41678, -3.70379],
    'Quito, en el ecuador' => [-0.18065, -78.46784],
    'Tromsø, muy al norte' => [69.64921, 18.95508],
]);

it('dos casas de la misma calle caen en el mismo punto', function () {
    // Dos portales a unos 20 m, dentro de la misma celda de 250 m
    expect(ApproximateLocation::round(41.403210, 2.156700))
        ->toBe(ApproximateLocation::round(41.403350, 2.156850));
});

it('redondear un punto ya redondeado no lo cambia', function () {
    $una = ApproximateLocation::round(41.40312, 2.15667);

    expect(ApproximateLocation::round(...$una))->toBe($una);
});

it('la rejilla mide ~250 m también en longitud, no en grados', function () {
    // En Barcelona, 250 m de longitud son ~0,003°; en grados fijos serían ~188 m
    [, $a] = ApproximateLocation::round(41.4, 2.150);
    [, $b] = ApproximateLocation::round(41.4, 2.150 + 0.003);

    expect(metrosEntre(41.4, $a, 41.4, $b))->toBeGreaterThan(225)->toBeLessThan(275);
});
