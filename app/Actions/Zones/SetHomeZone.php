<?php

namespace App\Actions\Zones;

use App\Enums\ZoneKind;
use App\Enums\ZoneMode;
use App\Enums\ZoneSource;
use App\Geo\ApproximateLocation;
use App\Models\User;
use App\Models\UserZone;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Fija la zona habitual de un miembro (#18): un punto con radio. El punto se
 * redondea a ~250 m antes de guardarlo y la zona anterior se sustituye.
 * La usa la web y, más adelante, la API (#73).
 */
class SetHomeZone
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function set(User $user, array $input): UserZone
    {
        /** @var array{latitude: numeric, longitude: numeric, radius_m: numeric, source: string} $data */
        $data = Validator::make($input, self::rules())->validateWithBag('updateZone');

        [$lat, $lon] = ApproximateLocation::round((float) $data['latitude'], (float) $data['longitude']);

        return UserZone::updateOrCreate(
            ['user_id' => $user->id, 'kind' => ZoneKind::Home],
            [
                'mode' => ZoneMode::Radius,
                'source' => ZoneSource::from($data['source']),
                'location' => Point::makeGeodetic($lat, $lon),
                'radius_m' => (int) $data['radius_m'],
                // Guardar una zona es querer usarla: se activa aunque estuviera apagada
                'is_active' => true,
            ],
        );
    }

    /**
     * El interruptor de Mi zona (#99): apaga o enciende la zona sin borrarla.
     * Sin zona guardada no hace nada y devuelve false.
     */
    public function setActive(User $user, bool $active): bool
    {
        return $user->homeZone()->update(['is_active' => $active]) > 0;
    }

    public function remove(User $user): void
    {
        $user->homeZone()->delete();
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_m' => [
                'required', 'integer', 'between:'.UserZone::MIN_RADIUS_M.','.UserZone::MAX_RADIUS_M,
                'multiple_of:'.UserZone::RADIUS_STEP_M,
            ],
            'source' => ['required', Rule::in([ZoneSource::Geolocation->value, ZoneSource::Map->value])],
        ];
    }
}
