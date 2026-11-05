<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/*
| Mi cuenta (#17): avatar, bio y contraseña
*/

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create([
        'username' => 'zStrikke',
        'password' => 'contraseña-vieja',
    ]);
});

describe('acceso', function () {
    it('pide iniciar sesión', function () {
        $this->get('/account')->assertRedirect('/login');
    });

    it('pide el email verificado', function () {
        $this->actingAs(User::factory()->unverified()->create())
            ->get('/account')
            ->assertRedirect(route('verification.notice'));
    });

    it('muestra las secciones y la vista previa de la ficha', function () {
        $this->actingAs($this->user)->get('/account')
            ->assertOk()
            ->assertSeeText(['Mi cuenta', 'Perfil', 'Así te ven', 'Contraseña', 'Mi zona'])
            ->assertSee('Nº '.$this->user->memberNumber());
    });

    it('la cabecera enlaza a Mi cuenta', function () {
        $this->actingAs($this->user)->get('/')->assertSee(route('account'), false);
    });
});

describe('perfil', function () {
    it('guarda la bio sin espacios sobrantes y la muestra en el perfil público', function () {
        $this->actingAs($this->user)
            ->patch('/account/profile', ['bio' => "  Leo en el tren.\n  "])
            ->assertRedirect('/account')
            ->assertSessionHasNoErrors();

        expect($this->user->fresh()->bio)->toBe('Leo en el tren.');
        $this->get('/u/zStrikke')->assertSeeText('Leo en el tren.');
    });

    it('una bio vacía se guarda como null', function () {
        $this->user->forceFill(['bio' => 'Antes'])->save();

        $this->actingAs($this->user)->patch('/account/profile', ['bio' => '   ']);

        expect($this->user->fresh()->bio)->toBeNull();
    });

    it('la bio no pasa de 500 caracteres', function () {
        $this->actingAs($this->user)
            ->from('/account')
            ->patch('/account/profile', ['bio' => str_repeat('a', 501)])
            ->assertRedirect('/account')
            ->assertSessionHasErrorsIn('updateProfile', ['bio' => 'La bio no puede tener más de 500 caracteres.']);

        expect($this->user->fresh()->bio)->toBeNull();
    });

    it('confirma el guardado junto al formulario, no en el aviso general', function () {
        $this->actingAs($this->user)
            ->followingRedirects()
            ->patch('/account/profile', ['bio' => 'Hola'])
            ->assertSeeText('✓ Perfil guardado')
            ->assertDontSee('profile-updated');
    });
});

describe('avatar', function () {
    it('lo recorta cuadrado, lo reduce a 256 px y lo guarda como WebP', function () {
        $this->actingAs($this->user)
            ->patch('/account/profile', ['avatar' => UploadedFile::fake()->image('foto.jpg', 800, 600)])
            ->assertSessionHasNoErrors();

        $ruta = $this->user->fresh()->avatar;
        expect($ruta)->toStartWith('avatars/')->toEndWith('.webp');
        Storage::disk('public')->assertExists($ruta);

        $info = getimagesizefromstring(Storage::disk('public')->get($ruta));
        expect([$info[0], $info[1], $info['mime']])->toBe([256, 256, 'image/webp']);
    });

    it('al cambiarlo borra el anterior', function () {
        $this->actingAs($this->user)->patch('/account/profile', ['avatar' => UploadedFile::fake()->image('a.png', 300, 300)]);
        $anterior = $this->user->fresh()->avatar;

        $this->actingAs($this->user)->patch('/account/profile', ['avatar' => UploadedFile::fake()->image('b.png', 300, 300)]);

        Storage::disk('public')->assertMissing($anterior);
        Storage::disk('public')->assertExists($this->user->fresh()->avatar);
    });

    it('guardar solo la bio no toca el avatar', function () {
        $this->actingAs($this->user)->patch('/account/profile', ['avatar' => UploadedFile::fake()->image('a.png', 300, 300)]);
        $avatar = $this->user->fresh()->avatar;

        $this->actingAs($this->user)->patch('/account/profile', ['bio' => 'Nueva']);

        expect($this->user->fresh()->avatar)->toBe($avatar);
    });

    it('rechaza lo que no es JPG, PNG o WebP y lo que pesa más de 2 MB', function (UploadedFile $fichero) {
        $this->actingAs($this->user)
            ->patch('/account/profile', ['avatar' => $fichero])
            ->assertSessionHasErrorsIn('updateProfile', 'avatar');

        expect($this->user->fresh()->avatar)->toBeNull();
    })->with([
        'gif' => fn () => UploadedFile::fake()->image('foto.gif', 100, 100),
        'pdf' => fn () => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        '3 MB' => fn () => UploadedFile::fake()->image('foto.jpg', 100, 100)->size(3000),
    ]);

    it('quitar la foto la borra y vuelven las iniciales', function () {
        $this->actingAs($this->user)->patch('/account/profile', ['avatar' => UploadedFile::fake()->image('a.png', 300, 300)]);
        $ruta = $this->user->fresh()->avatar;

        $this->actingAs($this->user)->delete('/account/avatar')->assertRedirect('/account');

        expect($this->user->fresh()->avatar)->toBeNull();
        Storage::disk('public')->assertMissing($ruta);
        $this->get('/u/zStrikke')->assertSee('role="img" aria-label="Avatar de zStrikke"', false);
    });
});

describe('contraseña', function () {
    it('la cambia si la actual es correcta', function () {
        $this->actingAs($this->user)
            ->from('/account')
            ->put('/user/password', [
                'current_password' => 'contraseña-vieja',
                'password' => 'una-contraseña-nueva',
                'password_confirmation' => 'una-contraseña-nueva',
            ])
            ->assertRedirect('/account')
            ->assertSessionHasNoErrors();

        expect(Hash::check('una-contraseña-nueva', $this->user->fresh()->password))->toBeTrue();
    });

    it('no la cambia si la actual no es correcta o la nueva no se repite igual', function (array $datos, string $campo) {
        $this->actingAs($this->user)
            ->from('/account')
            ->put('/user/password', $datos)
            ->assertSessionHasErrorsIn('updatePassword', $campo);

        expect(Hash::check('contraseña-vieja', $this->user->fresh()->password))->toBeTrue();
    })->with([
        'actual incorrecta' => [['current_password' => 'otra', 'password' => 'una-contraseña-nueva', 'password_confirmation' => 'una-contraseña-nueva'], 'current_password'],
        'no coinciden' => [['current_password' => 'contraseña-vieja', 'password' => 'una-contraseña-nueva', 'password_confirmation' => 'otra-distinta'], 'password'],
        'demasiado corta' => [['current_password' => 'contraseña-vieja', 'password' => 'corta', 'password_confirmation' => 'corta'], 'password'],
    ]);

    it('confirma el cambio junto al formulario', function () {
        $this->actingAs($this->user)
            ->from('/account')
            ->followingRedirects()
            ->put('/user/password', [
                'current_password' => 'contraseña-vieja',
                'password' => 'una-contraseña-nueva',
                'password_confirmation' => 'una-contraseña-nueva',
            ])
            ->assertSeeText('✓ Contraseña cambiada')
            ->assertDontSee('password-updated');
    });
});
