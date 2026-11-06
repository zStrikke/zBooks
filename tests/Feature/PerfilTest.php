<?php

use App\Models\User;
use App\Users\ProfileStats;
use Illuminate\Support\Carbon;

/*
| Perfil (#16). No es público (#93): solo lo ve el propio miembro (y sus amigos, con la #37)
*/

beforeEach(function () {
    Carbon::setTestNow('2026-11-05 12:00:00');
    $this->user = User::factory()->create([
        'username' => 'zStrikke',
        'email' => 'zstrikke@example.com',
        'bio' => "Leo en el tren.\nDejo los libros en Gràcia.",
    ]);
});

describe('quién lo ve', function () {
    it('sin sesión pide iniciarla', function () {
        $this->get('/u/zStrikke')->assertRedirect('/login');
    });

    it('otro miembro recibe un 404, como si no existiera', function (string $url) {
        $this->actingAs(User::factory()->create())->get($url)->assertNotFound();
    })->with(['/u/zStrikke', '/u/zstrikke']);

    it('un 404 igual que el de un username que no existe: no sirve para averiguarlos', function () {
        $otro = User::factory()->create();

        $existe = $this->actingAs($otro)->get('/u/zStrikke');
        $noExiste = $this->actingAs($otro)->get('/u/nadie');

        expect($existe->status())->toBe($noExiste->status())->toBe(404);
    });

    it('pide el email verificado', function () {
        $this->actingAs($this->user->forceFill(['email_verified_at' => null]))
            ->get('/u/zStrikke')
            ->assertRedirect(route('verification.notice'));
    });
});

it('el propio miembro ve su perfil', function () {
    $this->actingAs($this->user)->get('/u/zStrikke')
        ->assertOk()
        ->assertSeeText('Ficha de socio')
        ->assertSeeText('zStrikke')
        ->assertSeeText('Nº '.$this->user->memberNumber())
        ->assertSeeText('desde nov. 2026')
        ->assertSeeText('Leo en el tren.')
        ->assertSeeText('libros registrados')
        ->assertSeeText('Aquí irá su blog');
});

it('redirige (301) cualquier otra forma del username a la original', function (string $url) {
    $this->actingAs($this->user)->get($url)->assertStatus(301)->assertRedirect('/u/zStrikke');
})->with(['/u/zstrikke', '/u/ZSTRIKKE', '/u/ZStrikke']);

it('responde 404 si el miembro no existe o el username no es válido', function (string $url) {
    $this->actingAs($this->user)->get($url)->assertNotFound();
})->with(['/u/nadie', '/u/con-guion', '/u/'.str_repeat('a', 21)]);

it('muestra las iniciales si no hay avatar', function (string $username, string $iniciales) {
    $user = User::factory()->create(['username' => $username]);

    expect($user->initials())->toBe($iniciales);
    $this->actingAs($user)->get('/u/'.$username)->assertSee('aria-label="Avatar de '.$username.'"', false);
})->with([
    ['lectora', 'LE'],
    ['lector_1984', 'LE'],
    ['anaGarcia', 'AG'],
    ['x9', 'X9'],
]);

it('el número de socio es el id con ceros delante', function () {
    expect((new User)->forceFill(['id' => 42])->memberNumber())->toBe('000042')
        ->and((new User)->forceFill(['id' => 1234567])->memberNumber())->toBe('1234567');
});

it('la ficha lleva el sello de alta y la firma del miembro', function () {
    $this->actingAs($this->user)->get('/u/zStrikke')
        ->assertSee('SOCIO APROBADO', false)
        ->assertSeeInOrder(['>NOV<', '>2026<'], false)
        ->assertSee('font-firma', false);
});

it('no muestra el email', function () {
    $this->actingAs($this->user)->get('/u/zStrikke')->assertDontSee('zstrikke@example.com');
});

it('escapa la bio: el HTML se muestra como texto', function () {
    $this->user->forceFill(['bio' => '<script>alert(1)</script>'])->save();

    $this->actingAs($this->user)->get('/u/zStrikke')
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;', false);
});

it('sin bio no deja un párrafo vacío', function () {
    $this->user->forceFill(['bio' => null])->save();

    $this->actingAs($this->user)->get('/u/zStrikke')->assertOk()->assertDontSee('whitespace-pre-line', false);
});

it('las cifras son 0 hasta que existan los libros (v0.3.0)', function () {
    expect(ProfileStats::for($this->user))->toEqual(new ProfileStats(0, 0, 0));
});

it('con la sesión iniciada, la cabecera enlaza a mi perfil', function () {
    $this->actingAs($this->user)->get('/')->assertSee(route('profile.show', 'zStrikke'), false);
});
