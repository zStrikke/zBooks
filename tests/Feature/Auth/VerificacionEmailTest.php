<?php

use App\Models\User;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
| Verificación del email (#10). Aviso en Blade y reenvío por el POST de Fortify (#69).
*/

beforeEach(function () {
    Notification::fake();
});

function enlaceDeVerificacion(User $user, int $minutos = 60): string
{
    return URL::temporarySignedRoute('verification.verify', now()->addMinutes($minutos), [
        'id' => $user->getKey(),
        'hash' => sha1($user->getEmailForVerification()),
    ]);
}

it('el aviso pide iniciar sesión', function () {
    $this->get('/email/verify')->assertRedirect();
});

it('el aviso muestra el email y cuánto dura el enlace', function () {
    $user = User::factory()->unverified()->create(['email' => 'zstrikke@example.com']);

    $this->actingAs($user)->get('/email/verify')
        ->assertOk()
        ->assertSeeText('Revisa tu correo')
        ->assertSeeText('zstrikke@example.com')
        ->assertSeeText('caduca en 60 min');
});

it('un usuario ya verificado no ve el aviso', function () {
    $this->actingAs(User::factory()->create())
        ->get('/email/verify')
        ->assertRedirect(route('home'));
});

it('reenvía el email y vuelve al aviso con la confirmación', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->from('/email/verify')
        ->post('/email/verification-notification')
        ->assertRedirect(route('verification.notice'));

    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);

    $this->get('/email/verify')
        ->assertSeeText('Te hemos enviado un enlace nuevo.')
        ->assertSeeText('Podrás reenviarlo en');
});

it('reenvía como mucho un email por minuto, sin página de error', function () {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user);

    $this->post('/email/verification-notification');
    $this->post('/email/verification-notification')->assertRedirect(route('verification.notice'));
    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);

    $this->travel(61)->seconds();
    $this->post('/email/verification-notification');
    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 2);
});

it('la cuenta atrás del aviso es el límite real del servidor', function () {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user);

    // Sin envíos recientes, el botón está activo
    $this->get('/email/verify')->assertSee('wait: 0', false);

    $this->post('/email/verification-notification');
    $this->travel(20)->seconds();

    $this->get('/email/verify')->assertSee('wait: 40', false)->assertSeeText('0:40');
});

it('justo después de registrarse hay que esperar un minuto para reenviar', function () {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user)->withSession(['auth.just_registered' => true]);

    $this->get('/email/verify')->assertSee('wait: 60', false);
    $this->post('/email/verification-notification');

    Notification::assertNothingSent();
});

it('el enlace verifica la cuenta y muestra la confirmación', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get(enlaceDeVerificacion($user))
        ->assertRedirect(route('home'))
        ->assertSessionHas('status');

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

    $this->get(route('home'))->assertSeeText('Tu email está verificado');
});

it('un enlace caducado no verifica', function () {
    $user = User::factory()->unverified()->create();
    $enlace = enlaceDeVerificacion($user);

    $this->travel(61)->minutes();

    $this->actingAs($user)->get($enlace)->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('un enlace manipulado no verifica', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get(enlaceDeVerificacion($user).'x')->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('sin verificar se navega, pero las rutas que escriben llevan al aviso', function () {
    Route::middleware(['web', 'auth', 'verified'])->post('/_prueba-escritura', fn () => 'ok');
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get(route('home'))
        ->assertOk()
        ->assertSeeText('Verifica tu email para registrar, liberar o capturar libros.');

    $this->post('/_prueba-escritura')->assertRedirect(route('verification.notice'));

    $user->markEmailAsVerified();
    $this->post('/_prueba-escritura')->assertOk();
});
