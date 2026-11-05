<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Username en lugar de name (#10). Se guarda tal como lo escribe el usuario
 * (zStrikke) y es único sin distinguir mayúsculas: índice sobre lower(username).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 20)->nullable()->after('id');
        });

        // Cuentas de desarrollo anteriores a esta migración
        DB::table('users')->whereNull('username')->update(['username' => DB::raw("'user_' || id")]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 20)->nullable(false)->change();
            $table->dropColumn('name');
        });

        DB::statement('CREATE UNIQUE INDEX users_username_lower_unique ON users (lower(username))');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS users_username_lower_unique');

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->default('')->after('id');
        });

        DB::table('users')->update(['name' => DB::raw('username')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
