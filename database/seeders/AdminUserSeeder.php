<?php

namespace Database\Seeders;

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

        $this->call(RoleSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => config('zbooks.dev_admin.email')],
            [
                'name' => config('zbooks.dev_admin.name'),
                'password' => config('zbooks.dev_admin.password'),
            ],
        );

        $admin->assignRole('admin');
    }
}
