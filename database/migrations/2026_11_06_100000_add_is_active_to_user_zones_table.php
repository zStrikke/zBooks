<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Interruptor de Mi zona (#99): una zona desactivada se conserva, pero no cuenta
| para «Libros cerca» ni para los avisos (v0.5.0) hasta que se reactive.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_zones', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('radius_m');
        });
    }

    public function down(): void
    {
        Schema::table('user_zones', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
