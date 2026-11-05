<?php

use App\Livewire\Auth\VerifyEmail;
use App\Models\User;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

/*
| Verificación del email (#10)
*/

beforeEach(function () {
    Notification::fake();
    app()->setLocale('es');
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
    $this->actingAs(User::factory()->create());

    Livewire::test(VerifyEmail::class)->assertRedirect(route('home'));
});

it('reenvía el email', function () {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->call('resend')
        ->assertSet('sent', true)
        ->assertSet('wait', 60);

    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);
});

it('reenvía como mucho un email por minuto', function () {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user);

    $component = Livewire::test(VerifyEmail::class)->call('resend')->call('resend');
    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 1);

    $this->travel(61)->seconds();
    $component->call('resend');
    Notification::assertSentToTimes($user, VerifyEmailNotification::class, 2);
});

it('justo después de registrarse hay que esperar un minuto para reenviar', function () {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user)->withSession(['auth.just_registered' => true]);

    Livewire::test(VerifyEmail::class)
        ->assertSet('wait', 60)
        ->call('resend');

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
