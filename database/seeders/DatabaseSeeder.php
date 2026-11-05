<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Los roles se crean en todos los entornos; el admin, solo fuera de producción.
        $this->call([RoleSeeder::class, AdminUserSeeder::class]);

        User::factory()->create([
            'username' => 'test_user',
            'email' => 'test@example.com',
        ]);
    }
}
