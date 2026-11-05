<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
| Zonas de los miembros (#18, ADR-0013). De momento, solo el modo radio:
| `area_id` llega con el catálogo de barrios (#54) y `label` y `ends_at`, con
| la zona temporal (#19).
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 16);
            $table->string('mode', 16);
            $table->string('source', 16);
            // Siempre redondeado a ~500 m (App\Geo\ApproximateLocation): nunca el punto exacto
            $table->geography('location', 'point', 4326)->nullable();
            $table->unsignedSmallInteger('radius_km')->nullable();
            $table->timestamps();

            $table->spatialIndex('location');
        });

        DB::statement("CREATE UNIQUE INDEX user_zones_one_home ON user_zones (user_id) WHERE kind = 'home'");
        DB::statement("ALTER TABLE user_zones ADD CONSTRAINT user_zones_kind CHECK (kind IN ('home', 'temporary'))");
        DB::statement("ALTER TABLE user_zones ADD CONSTRAINT user_zones_source CHECK (source IN ('geolocation', 'map', 'area'))");
        // Modo radio: punto y radio de 1 a 50 km. El modo área se añade con su columna (#54)
        DB::statement("ALTER TABLE user_zones ADD CONSTRAINT user_zones_mode CHECK (
            mode = 'radius' AND location IS NOT NULL AND radius_km BETWEEN 1 AND 50
        )");
    }

    public function down(): void
    {
        Schema::dropIfExists('user_zones');
    }
};
