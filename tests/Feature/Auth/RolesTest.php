<?php

use App\Enums\Role as RoleName;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Role;

/*
| Roles base (#15). Los crea una migración (#71): existen en todos los entornos
| sin lanzar ningún seeder, también en estos tests.
*/

it('la base de datos tiene exactamente los roles del enum', function () {
    // Si se añade un caso al enum sin su migración (o al revés), falla.
    // toEqualCanonicalizing ignora el orden pero no admite roles de más o de menos.
    expect(Role::pluck('name')->all())
        ->toEqualCanonicalizing(array_column(RoleName::cases(), 'value'));
});

it('el enum tiene los roles que pide la issue', function () {
    expect(array_column(RoleName::cases(), 'value'))->toBe(['member', 'moderator', 'admin']);
});

it('la migración de roles se puede deshacer y rehacer sin duplicar', function () {
    $migracion = require database_path('migrations/2026_11_05_100000_create_base_roles.php');

    $migracion->down();
    expect(Role::count())->toBe(0);

    $migracion->up();
    $migracion->up();
    expect(Role::count())->toBe(3);
});

it('el seeder crea un usuario admin de desarrollo con su rol', function () {
    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', config('zbooks.dev_admin.email'))->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole(RoleName::Admin))->toBeTrue();
});

it('en producción el seeder no crea el usuario admin', function () {
    $this->app['env'] = 'production';

    // En producción db:seed se niega a ejecutarse sin --force: sin él, el test pasaría sin sembrar nada.
    $this->artisan('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true])->assertSuccessful();

    expect(User::where('email', config('zbooks.dev_admin.email'))->exists())->toBeFalse()
        ->and(Role::count())->toBe(3);
});

it('hasRole() distingue los roles del usuario', function (RoleName $role, bool $expected) {
    $user = User::factory()->create();
    $user->assignRole(RoleName::Moderator);

    expect($user->hasRole($role))->toBe($expected);
})->with([
    [RoleName::Moderator, true],
    [RoleName::Admin, false],
    [RoleName::Member, false],
]);

it('un usuario sin rol no tiene ninguno', function (RoleName $role) {
    $user = User::factory()->create();

    expect($user->hasRole($role))->toBeFalse();
})->with(RoleName::cases());
