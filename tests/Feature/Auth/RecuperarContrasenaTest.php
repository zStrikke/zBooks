<?php

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

/*
| He olvidado mi contraseña (#11)
*/

beforeEach(function () {
    Notification::fake();
    $this->user = User::factory()->create(['email' => 'zstrikke@example.com']);
});

it('muestra el formulario', function () {
    $this->get('/forgot-password')->assertOk()->assertSeeText('¿Has olvidado tu contraseña?');
});

it('envía el enlace en cola y en el idioma de la web', function () {
    $this->from('/forgot-password')
        ->post('/forgot-password', ['email' => 'ZStrikke@example.com'])
        ->assertRedirect('/forgot-password')
        ->assertSessionHas('status');

    Notification::assertSentTo($this->user, ResetPassword::class, fn (ResetPassword $n) => $n->locale === 'es');
    expect(new ResetPassword('token'))->toBeInstanceOf(ShouldQueue::class);
});

it('no revela si un email tiene cuenta', function () {
    $this->from('/forgot-password')
        ->post('/forgot-password', ['email' => 'nadie@example.com'])
        ->assertRedirect('/forgot-password')
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', __('passwords.sent', locale: 'es'));

    Notification::assertNothingSent();
});

it('el enlace lleva a la pantalla de nueva contraseña con el email', function () {
    $token = Password::createToken($this->user);

    $this->get('/reset-password/'.$token.'?email=zstrikke@example.com')
        ->assertOk()
        ->assertSeeText('Elige una contraseña nueva')
        ->assertSee('value="zstrikke@example.com"', false)
        ->assertSee($token, false);
});

it('cambia la contraseña y lleva al login', function () {
    $token = Password::createToken($this->user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => 'zstrikke@example.com',
        'password' => 'otra-clave-larga',
    ])->assertRedirect('/login')->assertSessionHas('status');

    expect(Hash::check('otra-clave-larga', $this->user->fresh()->password))->toBeTrue();

    $this->post('/login', ['login' => 'zstrikke@example.com', 'password' => 'otra-clave-larga']);
    $this->assertAuthenticatedAs($this->user);
});

it('la contraseña nueva cumple las mismas reglas que en el registro', function () {
    $token = Password::createToken($this->user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => 'zstrikke@example.com',
        'password' => 'corta',
    ])->assertSessionHasErrors('password');
});

it('un enlace caducado o falso no sirve', function () {
    $token = Password::createToken($this->user);
    $this->travel(61)->minutes();

    $this->from('/reset-password/'.$token)->post('/reset-password', [
        'token' => $token,
        'email' => 'zstrikke@example.com',
        'password' => 'otra-clave-larga',
    ])->assertSessionHasErrors('email');

    $this->post('/reset-password', [
        'token' => 'falso',
        'email' => 'zstrikke@example.com',
        'password' => 'otra-clave-larga',
    ])->assertSessionHasErrors('email');
});
