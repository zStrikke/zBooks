<?php

namespace Database\Seeders;

use App\Enums\Role as RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Crea los roles base. Es idempotente: puede ejecutarse varias veces.
     */
    public function run(): void
    {
        // Spatie cachea roles y permisos; hay que limpiarla para ver los cambios.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (RoleName::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }
    }
}
