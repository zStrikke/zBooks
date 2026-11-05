<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

/**
 * Roles base (#15, #71). Son datos de referencia que la aplicación necesita en
 * todos los entornos, por eso van en una migración y no en un seeder: salen con
 * el `migrate` del despliegue.
 *
 * Los nombres van escritos aquí y no se leen del enum App\Enums\Role: una
 * migración es una foto de su momento. Un rol nuevo será una migración nueva
 * (RolesTest comprueba que la base de datos y el enum coinciden).
 */
return new class extends Migration
{
    private const ROLES = ['member', 'moderator', 'admin'];

    public function up(): void
    {
        $now = now();

        DB::table('roles')->insertOrIgnore(array_map(fn (string $name) => [
            'name' => $name,
            'guard_name' => 'web',
            'created_at' => $now,
            'updated_at' => $now,
        ], self::ROLES));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        DB::table('roles')->where('guard_name', 'web')->whereIn('name', self::ROLES)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
