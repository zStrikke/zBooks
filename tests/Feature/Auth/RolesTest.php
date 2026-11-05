<?php

use App\Enums\Role as RoleName;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RoleSeeder;
use Spatie\Permission\Models\Role;

/*
| Roles base (#15): member, moderator y admin.
*/

it('crea los tres roles base', function () {
    $this->seed(RoleSeeder::class);

    // toEqualCanonicalizing ignora el orden pero falla si se cuela un cuarto rol.
    expect(Role::pluck('name')->all())
        ->toEqualCanonicalizing(array_column(RoleName::cases(), 'value'));
});

it('el enum tiene los roles que pide la issue', function () {
    expect(array_column(RoleName::cases(), 'value'))->toBe(['member', 'moderator', 'admin']);
});

it('no duplica los roles si el seeder se ejecuta dos veces', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(RoleSeeder::class);

    expect(Role::count())->toBe(3);
});

it('el seeder crea un usuario admin de desarrollo con su rol', function () {
    $this->seed([RoleSeeder::class, AdminUserSeeder::class]);

    $admin = User::where('email', config('zbooks.dev_admin.email'))->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole(RoleName::Admin))->toBeTrue();
});

it('en producción crea los roles pero no el usuario admin', function () {
    $this->app['env'] = 'production';

    // En producción db:seed se niega a ejecutarse sin --force: sin él, el test pasaría sin sembrar nada.
    $this->artisan('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true])->assertSuccessful();

    expect(Role::count())->toBe(3)
        ->and(User::where('email', config('zbooks.dev_admin.email'))->exists())->toBeFalse();
});

it('hasRole() distingue los roles del usuario', function (RoleName $role, bool $expected) {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole(RoleName::Moderator);

    expect($user->hasRole($role))->toBe($expected);
})->with([
    [RoleName::Moderator, true],
    [RoleName::Admin, false],
    [RoleName::Member, false],
]);

it('un usuario sin rol no tiene ninguno', function (RoleName $role) {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();

    expect($user->hasRole($role))->toBeFalse();
})->with(RoleName::cases());
