<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea el usuario admin de desarrollo. Nunca en producción.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => config('zbooks.dev_admin.email')],
            [
                'username' => config('zbooks.dev_admin.username'),
                'password' => config('zbooks.dev_admin.password'),
            ],
        );

        $admin->assignRole(Role::Admin);
    }
}
