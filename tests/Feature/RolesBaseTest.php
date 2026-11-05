<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RoleSeeder;
use Spatie\Permission\Models\Role;

/*
| Roles base (#15): member, moderator y admin.
*/

it('crea los tres roles base', function () {
    $this->seed(RoleSeeder::class);

    // toEqualCanonicalizing ignora el orden pero falla si se cuela un cuarto rol.
    expect(Role::pluck('name')->all())->toEqualCanonicalizing(['member', 'moderator', 'admin']);
});

it('no duplica los roles si el seeder se ejecuta dos veces', function () {
    $this->seed(RoleSeeder::class);
    $this->seed(RoleSeeder::class);

    expect(Role::count())->toBe(3);
});

it('el seeder crea un usuario admin de desarrollo con su rol', function () {
    $this->seed(AdminUserSeeder::class);

    $admin = User::where('email', config('zbooks.dev_admin.email'))->first();

    expect($admin)->not->toBeNull()
        ->and($admin->hasRole('admin'))->toBeTrue();
});

it('no crea el usuario admin en producción', function () {
    $this->app['env'] = 'production';

    $this->seed(AdminUserSeeder::class);

    expect(User::where('email', config('zbooks.dev_admin.email'))->exists())->toBeFalse();
});

it('hasRole() distingue los roles del usuario', function (string $role, bool $expected) {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('moderator');

    expect($user->hasRole($role))->toBe($expected);
})->with([
    ['moderator', true],
    ['admin', false],
    ['member', false],
]);

it('un usuario sin rol no tiene ninguno', function (string $role) {
    $this->seed(RoleSeeder::class);
    $user = User::factory()->create();

    expect($user->hasRole($role))->toBeFalse();
})->with(['member', 'moderator', 'admin']);
