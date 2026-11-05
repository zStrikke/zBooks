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

        // Solo datos de desarrollo. Los roles los crea una migración (#71);
        // el admin, solo fuera de producción.
        $this->call(AdminUserSeeder::class);

        User::factory()->create([
            'username' => 'test_user',
            'email' => 'test@example.com',
        ]);
    }
}
