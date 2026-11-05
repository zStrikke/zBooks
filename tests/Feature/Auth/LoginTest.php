<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
| Login y logout (#11)
*/

beforeEach(function () {
    $this->user = User::factory()->create([
        'username' => 'zStrikke',
        'email' => 'zstrikke@example.com',
        'password' => 'una-clave-larga',
    ]);
});

it('muestra el formulario en español', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSeeText('Hola de nuevo')
        ->assertSeeText('Email o nombre de usuario')
        ->assertSee(route('password.request'), false)
        ->assertSee(route('register'), false);
});

it('entra con el email o con el username, sin distinguir mayúsculas', function (string $login) {
    $this->post('/login', ['login' => $login, 'password' => 'una-clave-larga'])
        ->assertRedirect('/');

    $this->assertAuthenticatedAs($this->user);
})->with(['zstrikke@example.com', 'ZStrikke@Example.com', 'zStrikke', 'zstrikke', 'ZSTRIKKE']);

it('vuelve a la página que pedía antes de entrar', function () {
    $this->get('/email/verify')->assertRedirect(route('login'));

    $this->post('/login', ['login' => 'zStrikke', 'password' => 'una-clave-larga'])
        ->assertRedirect('/email/verify');
});

it('rechaza credenciales incorrectas con un mensaje en español', function (string $login, string $password) {
    // assertSessionHasErrors() vacía la sesión: se comprueba el mensaje al volver al formulario
    $this->from('/login')
        ->post('/login', ['login' => $login, 'password' => $password])
        ->assertRedirect('/login');

    $this->assertGuest();
    $this->get('/login')
        ->assertSee('role="alert"', false)
        ->assertSeeText('El email, el nombre de usuario o la contraseña no son correctos.')
        // Fortify lo pasa a minúsculas (fortify.lowercase_usernames)
        ->assertSee('value="'.mb_strtolower($login).'"', false);
})->with([
    'contraseña mal' => ['zStrikke', 'otra-clave'],
    'usuario que no existe' => ['nadie', 'una-clave-larga'],
    'email que no existe' => ['nadie@example.com', 'una-clave-larga'],
]);

it('bloquea tras 5 intentos fallidos en un minuto', function () {
    foreach (range(1, 5) as $intento) {
        $this->post('/login', ['login' => 'zStrikke', 'password' => 'mal']);
    }

    $this->from('/login')
        ->post('/login', ['login' => 'zStrikke', 'password' => 'una-clave-larga']);
    $this->assertGuest();
    $this->get('/login')->assertSeeText('Demasiados intentos');

    $this->travel(61)->seconds();
    $this->post('/login', ['login' => 'zStrikke', 'password' => 'una-clave-larga']);
    $this->assertAuthenticated();
});

it('«Mantener la sesión iniciada» guarda la cookie de recordar', function () {
    $response = $this->post('/login', ['login' => 'zStrikke', 'password' => 'una-clave-larga', 'remember' => '1']);

    $response->assertCookie(Auth::guard()->getRecallerName());
    expect($this->user->fresh()->remember_token)->not->toBeNull();
});

it('sin marcarla no hay cookie de recordar', function () {
    $this->post('/login', ['login' => 'zStrikke', 'password' => 'una-clave-larga'])
        ->assertCookieMissing(Auth::guard()->getRecallerName());
});

it('el logout cierra la sesión y la invalida', function () {
    $this->actingAs($this->user)->withSession(['dato' => 'secreto']);

    $this->post('/logout')->assertRedirect('/');

    $this->assertGuest();
    expect(session('dato'))->toBeNull();
});

it('con la sesión iniciada no se ve el login', function () {
    $this->actingAs($this->user)->get('/login')->assertRedirect();
});

it('la cabecera muestra Entrar o, con sesión, el usuario y Salir', function () {
    $this->get('/')->assertSee(route('login'), false);

    $this->actingAs($this->user)->get('/')
        ->assertSeeText('zStrikke')
        ->assertSee(route('logout'), false);
});
