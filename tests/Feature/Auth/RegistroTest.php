<?php

use App\Enums\Role as RoleName;
use App\Legal\PrivacyPolicy;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Database\Seeders\RoleSeeder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;

/*
| Registro en la web (#10). El formulario envía al POST /register de Fortify (#69).
*/

beforeEach(function () {
    $this->seed(RoleSeeder::class);
    Notification::fake();
});

/**
 * Envía el formulario como el navegador: desde /register y con la casilla marcada.
 *
 * @param  array<string, mixed>  $overrides
 */
function registrar(array $overrides = []): TestResponse
{
    return test()->from('/register')->post('/register', array_merge([
        'username' => 'zStrikke',
        'email' => 'zstrikke@example.com',
        'password' => 'una-clave-larga',
        'privacy_policy' => '1',
    ], $overrides));
}

it('muestra el formulario en español', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('<html lang="es"', false)
        ->assertSeeText('Crea tu cuenta')
        ->assertSeeText('Nombre de usuario')
        ->assertSeeText('política de privacidad')
        ->assertSee('action="'.route('register.store').'"', false)
        ->assertSee(route('legal.privacy'), false);
});

it('crea la cuenta, inicia la sesión y lleva al aviso de verificación', function () {
    registrar()->assertRedirect(route('verification.notice'));

    $user = User::sole();

    expect($user->username)->toBe('zStrikke')
        ->and($user->email)->toBe('zstrikke@example.com')
        ->and($user->hasVerifiedEmail())->toBeFalse()
        ->and($user->hasRole(RoleName::Member))->toBeTrue()
        ->and(PrivacyPolicy::hasAccepted($user))->toBeTrue();

    $this->assertAuthenticatedAs($user);
});

it('envía el email de verificación en cola y en el idioma de la web', function () {
    registrar();

    Notification::assertSentTo(User::sole(), VerifyEmail::class, function (VerifyEmail $notification) {
        return $notification->locale === 'es';
    });
    expect(new VerifyEmail)->toBeInstanceOf(ShouldQueue::class);
});

it('el email lleva un enlace firmado que caduca en 60 minutos', function () {
    registrar();
    $user = User::sole();

    $mail = (new VerifyEmail)->toMail($user);

    expect($mail->actionUrl)->toContain('/email/verify/'.$user->id.'/')
        ->toContain('expires='.now()->addMinutes(60)->getTimestamp())
        ->toContain('signature=');
});

it('guarda el email en minúsculas', function () {
    registrar(['email' => 'ZStrikke@Example.COM']);

    expect(User::sole()->email)->toBe('zstrikke@example.com');
});

it('valida cada campo', function (array $input, string $field) {
    registrar($input)->assertRedirect('/register')->assertSessionHasErrors($field);

    expect(User::count())->toBe(0);
    $this->assertGuest();
})->with([
    'sin username' => [['username' => ''], 'username'],
    'username corto' => [['username' => 'ab'], 'username'],
    'username largo' => [['username' => str_repeat('a', 21)], 'username'],
    'username con espacios' => [['username' => 'z strikke'], 'username'],
    'username con acentos' => [['username' => 'lectoría'], 'username'],
    'username con guion' => [['username' => 'z-strikke'], 'username'],
    'email no válido' => [['email' => 'no-es-un-email'], 'email'],
    'contraseña corta' => [['password' => 'corta'], 'password'],
    'sin aceptar la privacidad' => [['privacy_policy' => null], 'privacy_policy'],
]);

it('acepta usernames con letras, números y guion bajo', function (string $username) {
    registrar(['username' => $username])->assertSessionHasNoErrors();
})->with(['abc', 'zStrikke', 'lector_1984', str_repeat('a', 20)]);

it('el username es único sin distinguir mayúsculas', function () {
    User::factory()->create(['username' => 'zStrikke']);

    registrar(['username' => 'ZSTRIKKE', 'email' => 'otro@example.com'])
        ->assertSessionHasErrors(['username' => 'Ese nombre de usuario ya está en uso.']);
});

it('la base de datos tampoco admite el mismo username con otras mayúsculas', function () {
    User::factory()->create(['username' => 'zStrikke']);

    expect(fn () => User::factory()->create(['username' => 'zstrikke']))
        ->toThrow(UniqueConstraintViolationException::class);
});

it('el email es único', function () {
    User::factory()->create(['email' => 'zstrikke@example.com']);

    registrar(['email' => 'ZStrikke@example.com'])->assertSessionHasErrors('email');
});

it('no admite nombres reservados', function (string $username) {
    registrar(['username' => $username])
        ->assertSessionHasErrors(['username' => 'Ese nombre de usuario está reservado. Elige otro.']);
})->with(['admin', 'ADMIN', 'zBooks', 'api', 'soporte']);

it('muestra los errores en español y conserva lo escrito, salvo la contraseña', function () {
    // assertSessionHasErrors() vacía la sesión: se comprueba al volver al formulario
    registrar(['username' => 'admin', 'email' => 'lector@example.com', 'privacy_policy' => null]);

    $this->get('/register')
        ->assertSeeText('Ese nombre de usuario está reservado. Elige otro.')
        ->assertSeeText('Tienes que aceptar la política de privacidad para crear la cuenta.')
        ->assertSee('value="admin"', false)
        ->assertSee('value="lector@example.com"', false)
        ->assertDontSee('una-clave-larga');
});

it('en producción rechaza contraseñas filtradas', function () {
    $this->app['env'] = 'production';
    // Fuera de «testing» se comprueba el token CSRF; aquí no interesa
    $this->withoutMiddleware(PreventRequestForgery::class);
    // Simula la respuesta de haveibeenpwned.com: la contraseña aparece en filtraciones
    Http::fake(['api.pwnedpasswords.com/*' => Http::response(
        strtoupper(substr(sha1('password1234'), 5)).':12345',
    )]);

    registrar(['password' => 'password1234'])->assertSessionHasErrors('password');
});

it('un usuario con sesión iniciada no ve el registro', function () {
    $this->actingAs(User::factory()->create())
        ->get('/register')
        ->assertRedirect();
});
