<?php

use App\Models\User;
use App\Models\UserZone;
use Clickbar\Magellan\Data\Geometries\Point;

/*
| Menú de usuario de la cabecera (#95)
*/

beforeEach(function () {
    $this->user = User::factory()->create(['username' => 'zStrikke']);
});

it('sin sesión, la cabecera sigue con Entrar y Crear cuenta', function () {
    $this->get('/')
        ->assertOk()
        ->assertSeeText(['Entrar', 'Crear cuenta'])
        ->assertDontSee('id="menu-usuario"', false);
});

it('con sesión, el avatar abre el menú con saludo, cifras, zona y enlaces', function () {
    $this->actingAs($this->user)->get('/')
        ->assertOk()
        ->assertSee('aria-controls="menu-usuario"', false)
        ->assertSeeText('Hola, zStrikke')
        ->assertSeeInOrder(['0', 'liberados', '0', 'capturados'])
        ->assertSee(route('profile.show', 'zStrikke'), false)
        ->assertSee(route('account'), false)
        ->assertSee(route('account').'#zona', false)
        ->assertSee('action="'.route('logout').'"', false)
        ->assertSeeText(['Mi perfil', 'Mi cuenta', 'Cerrar sesión', 'Dar feedback'])
        ->assertDontSeeText('Entrar');
});

it('lo que aún no existe sale desactivado, con su versión', function () {
    $this->actingAs($this->user)->get('/')
        ->assertSeeInOrder(['Mis libros', 'v0.3.0', 'Mis capturas', 'v0.4.0', 'Amigos', 'v0.10.0']);
});

it('dice si la zona está activa', function () {
    $this->actingAs($this->user)->get('/')->assertSeeText('sin zona');

    UserZone::create([
        'user_id' => $this->user->id, 'kind' => 'home', 'mode' => 'radius', 'source' => 'map',
        'location' => Point::makeGeodetic(41.4, 2.15), 'radius_m' => 2500,
    ]);

    $this->actingAs($this->user)->get('/')->assertSeeText('activa')->assertDontSeeText('sin zona');
});

it('la campana abre las notificaciones, todas desactivadas hasta que lleguen', function () {
    $this->actingAs($this->user)->get('/')
        ->assertSee('aria-controls="menu-notificaciones"', false)
        ->assertSeeInOrder([
            'Tus notificaciones',
            'Mensajes privados', 'pronto',
            'Capturas de tus libros', 'v0.5.0',
            'Libros liberados cerca', 'v0.5.0',
            'Eventos', 'v0.9.0',
            'Solicitudes de amistad', 'v0.10.0',
        ]);
});

it('sin sesión no hay campana', function () {
    $this->get('/')->assertDontSee('menu-notificaciones', false);
});

it('en móvil, el panel lateral lleva el menú de usuario, la navegación y el buscador', function () {
    $this->actingAs($this->user)->get('/')
        ->assertSee('aria-controls="panel-movil"', false)
        ->assertSee('id="panel-movil"', false)
        ->assertSee('id="zbid-movil"', false);
});

it('sin sesión, el panel lateral lleva Entrar y Crear cuenta', function () {
    $this->get('/')
        ->assertSee('id="panel-movil"', false)
        ->assertDontSee('Hola,', false);
});
