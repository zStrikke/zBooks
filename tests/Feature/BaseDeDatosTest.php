<?php

use Illuminate\Support\Facades\DB;

it('usa PostgreSQL con PostGIS en los tests', function () {
    expect(DB::connection()->getDriverName())->toBe('pgsql')
        ->and(DB::connection()->getDatabaseName())->toBe('zbooks_testing');

    $extensiones = DB::table('pg_extension')->pluck('extname');

    expect($extensiones)->toContain('postgis', 'unaccent');
});

it('calcula distancias reales con PostGIS', function () {
    // Plaça de Catalunya → Sagrada Família, en línea recta: unos 2,3 km
    $metros = DB::scalar(<<<'SQL'
        SELECT ST_Distance(
            ST_SetSRID(ST_MakePoint(2.1700, 41.3870), 4326)::geography,
            ST_SetSRID(ST_MakePoint(2.1744, 41.4036), 4326)::geography
        )
    SQL);

    expect($metros)->toBeGreaterThan(1_800)->toBeLessThan(2_200);
});
