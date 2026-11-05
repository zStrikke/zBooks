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
 * @property int $radius_km
 */
class UserZone extends Model
{
    public const DEFAULT_RADIUS_KM = 5;

    public const MIN_RADIUS_KM = 1;

    public const MAX_RADIUS_KM = 50;

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
            'radius_km' => 'integer',
        ];
    }
}
