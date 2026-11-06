<?php

use App\Enums\ZoneKind;
use App\Enums\ZoneSource;
use App\Models\User;
use App\Models\UserZone;
use Illuminate\Database\QueryException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Testing\TestResponse;

/*
| Mi zona (#18): zona habitual con punto redondeado y radio
*/

beforeEach(function () {
    $this->user = User::factory()->create(['username' => 'zStrikke']);
    // Plaça del Sol (Gràcia), el punto exacto que nunca debe guardarse
    $this->casa = ['latitude' => 41.40312, 'longitude' => 2.15667];
});

function guardarZona(array $datos): TestResponse
{
    return test()->actingAs(test()->user)
        ->from('/account')
        ->put('/account/zone', [...test()->casa, 'radius_m' => 2500, 'source' => 'map', ...$datos]);
}

describe('guardar', function () {
    it('guarda el punto redondeado a ~250 m, nunca el exacto', function () {
        guardarZona([])->assertRedirect(route('account').'#zona')->assertSessionHasNoErrors();

        $zona = $this->user->homeZone;
        expect($zona->kind)->toBe(ZoneKind::Home)
            ->and($zona->source)->toBe(ZoneSource::Map)
            ->and($zona->radius_m)->toBe(2500)
            ->and([$zona->location->getLatitude(), $zona->location->getLongitude()])->not->toBe([41.40312, 2.15667]);

        // Distancia real en PostGIS entre lo guardado y la casa: más de 0 y menos de media celda
        $metros = DB::scalar('SELECT ST_Distance(location, ST_MakePoint(?, ?)::geography) FROM user_zones', [2.15667, 41.40312]);
        expect($metros)->toBeGreaterThan(0)->toBeLessThan(180);
    });

    it('al mudarse, la zona nueva sustituye a la anterior', function () {
        guardarZona([]);
        guardarZona(['latitude' => 40.41678, 'longitude' => -3.70379, 'radius_m' => 12000, 'source' => 'geolocation']);

        expect(UserZone::count())->toBe(1)
            ->and($this->user->fresh()->homeZone->radius_m)->toBe(12000)
            ->and(round($this->user->fresh()->homeZone->location->getLatitude()))->toBe(40.0);
    });

    it('rechaza radios fuera de 0,5–25 km o fuera de los pasos de 500 m, coordenadas imposibles y orígenes desconocidos', function (array $datos, string $campo) {
        guardarZona($datos)->assertSessionHasErrorsIn('updateZone', $campo);

        expect(UserZone::count())->toBe(0);
    })->with([
        'radio 0' => [['radius_m' => 0], 'radius_m'],
        'radio 25,5 km' => [['radius_m' => 25500], 'radius_m'],
        'radio 1,2 km' => [['radius_m' => 1200], 'radius_m'],
        'latitud 91' => [['latitude' => 91], 'latitude'],
        'longitud 181' => [['longitude' => 181], 'longitude'],
        'sin punto' => [['latitude' => null, 'longitude' => null], 'latitude'],
        'origen inventado' => [['source' => 'area'], 'source'],
    ]);

    it('quitar la zona la borra', function () {
        guardarZona([]);

        $this->actingAs($this->user)->delete('/account/zone')->assertRedirect(route('account').'#zona');

        expect(UserZone::count())->toBe(0);
    });

    it('pide sesión y email verificado', function () {
        $this->put('/account/zone', [...$this->casa, 'radius_m' => 2500, 'source' => 'map'])->assertRedirect('/login');
        $this->actingAs(User::factory()->unverified()->create())
            ->put('/account/zone', [...$this->casa, 'radius_m' => 2500, 'source' => 'map'])
            ->assertRedirect(route('verification.notice'));
    });
});

describe('base de datos', function () {
    it('no admite dos zonas habituales para el mismo miembro', function () {
        $fila = fn () => DB::table('user_zones')->insert([
            'user_id' => $this->user->id, 'kind' => 'home', 'mode' => 'radius', 'source' => 'map',
            'location' => DB::raw('ST_MakePoint(2.15, 41.4)::geography'), 'radius_m' => 2500,
        ]);
        $fila();

        expect($fila)->toThrow(QueryException::class);
    });

    it('no admite un radio fuera de 0,5–25 km o de los pasos de 500 m, ni una zona de radio sin punto', function (array $columnas) {
        expect(fn () => DB::table('user_zones')->insert([
            'user_id' => $this->user->id, 'kind' => 'home', 'mode' => 'radius', 'source' => 'map',
            'location' => DB::raw('ST_MakePoint(2.15, 41.4)::geography'), 'radius_m' => 2500, ...$columnas,
        ]))->toThrow(QueryException::class);
    })->with([
        'radio 30 km' => [['radius_m' => 30000]],
        'radio 1,2 km' => [['radius_m' => 1200]],
        'sin punto' => [['location' => null]],
    ]);
});

describe('privacidad', function () {
    it('la zona no aparece en el perfil, ni siquiera para el propio miembro', function () {
        guardarZona([]);
        $zona = $this->user->homeZone;

        $this->actingAs($this->user)->get('/u/zStrikke')
            ->assertOk()
            ->assertDontSee((string) $zona->location->getLatitude())
            ->assertDontSee((string) $zona->location->getLongitude())
            ->assertDontSee('41.40312');
    });

    it('Mi cuenta solo enseña el punto redondeado', function () {
        guardarZona([]);

        $this->actingAs($this->user)->get('/account')
            ->assertOk()
            ->assertSee((string) $this->user->homeZone->location->getLatitude())
            ->assertDontSee('41.40312');
    });
});

describe('buscador de direcciones', function () {
    beforeEach(fn () => RateLimiter::clear('nominatim'));

    it('pregunta a Nominatim identificándose y devuelve nombre y coordenadas', function () {
        Http::fake(['nominatim.openstreetmap.org/*' => Http::response([
            ['display_name' => 'Plaça del Sol, Gràcia, Barcelona', 'lat' => '41.4031', 'lon' => '2.1567'],
        ])]);

        $this->actingAs($this->user)->getJson('/account/zone/search?q=Plaça del Sol')
            ->assertOk()
            ->assertExactJson(['results' => [['name' => 'Plaça del Sol, Gràcia, Barcelona', 'latitude' => 41.4031, 'longitude' => 2.1567]]]);

        Http::assertSent(fn (Request $peticion) => str_starts_with($peticion->header('User-Agent')[0], 'zBooks/')
            && $peticion['q'] === 'plaça del sol'
            && $peticion['format'] === 'jsonv2');
    });

    it('guarda las respuestas en caché: la misma búsqueda no vuelve a Nominatim', function () {
        Http::fake(['nominatim.openstreetmap.org/*' => Http::response([])]);

        $this->actingAs($this->user)->getJson('/account/zone/search?q=Gràcia');
        RateLimiter::clear('nominatim');
        $this->actingAs($this->user)->getJson('/account/zone/search?q='.urlencode('  GRÀCIA '));

        Http::assertSentCount(1);
    });

    it('como mucho una petición por segundo a Nominatim entre todos', function () {
        Http::fake(['nominatim.openstreetmap.org/*' => Http::response([])]);

        $this->actingAs($this->user)->getJson('/account/zone/search?q=Gràcia')->assertOk();
        $this->actingAs($this->user)->getJson('/account/zone/search?q=Sants')
            ->assertStatus(429)
            ->assertJson(['message' => 'Demasiadas búsquedas. Prueba de nuevo en un momento.']);
    });

    it('con menos de 3 letras no busca, y si Nominatim falla devuelve una lista vacía', function () {
        Http::fake(['nominatim.openstreetmap.org/*' => Http::response('error', 500)]);

        $this->actingAs($this->user)->getJson('/account/zone/search?q=ab')->assertExactJson(['results' => []]);
        Http::assertNothingSent();

        $this->actingAs($this->user)->getJson('/account/zone/search?q=Gràcia')->assertExactJson(['results' => []]);
    });

    it('pide sesión', function () {
        $this->getJson('/account/zone/search?q=Gràcia')->assertUnauthorized();
    });
});
