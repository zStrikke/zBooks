<?php

namespace App\Models;

use App\Enums\ZoneKind;
use App\Enums\ZoneMode;
use App\Enums\ZoneSource;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Zona de un miembro (#18, ADR-0013). Privada: nunca sale en el perfil público.
 *
 * @property ZoneKind $kind
 * @property ZoneMode $mode
 * @property ZoneSource $source
 * @property Point $location
 * @property int $radius_m
 * @property bool $is_active Desactivada: se conserva, pero no cuenta para «Libros cerca» ni para avisos (#99)
 */
class UserZone extends Model
{
    /** Radio en metros: de 0,5 a 25 km, en pasos de 500 m (2,5 km por defecto) */
    public const DEFAULT_RADIUS_M = 2500;

    public const MIN_RADIUS_M = 500;

    public const MAX_RADIUS_M = 25000;

    public const RADIUS_STEP_M = 500;

    protected $guarded = ['id'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => ZoneKind::class,
            'mode' => ZoneMode::class,
            'source' => ZoneSource::class,
            'location' => Point::class,
            'radius_m' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
