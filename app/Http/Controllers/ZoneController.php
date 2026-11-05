<?php

namespace App\Http\Controllers;

use App\Actions\Zones\SetHomeZone;
use App\Geo\Nominatim;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Mi zona (#18): guardar o quitar la zona habitual y buscar direcciones.
 */
class ZoneController extends Controller
{
    public const UPDATED = 'zone-updated';

    public const REMOVED = 'zone-removed';

    public function update(Request $request, SetHomeZone $action): RedirectResponse
    {
        $action->set($this->user($request), $request->only(['latitude', 'longitude', 'radius_m', 'source']));

        return redirect(route('account').'#zona')->with('status', self::UPDATED);
    }

    public function destroy(Request $request, SetHomeZone $action): RedirectResponse
    {
        $action->remove($this->user($request));

        return redirect(route('account').'#zona')->with('status', self::REMOVED);
    }

    public function search(Request $request, Nominatim $nominatim): JsonResponse
    {
        $consulta = trim((string) $request->query('q', ''));

        if (mb_strlen($consulta) < 3) {
            return response()->json(['results' => []]);
        }

        $resultados = $nominatim->search($consulta, app()->getLocale());

        return $resultados === null
            ? response()->json(['message' => __('Too many searches. Try again in a moment.')], 429)
            : response()->json(['results' => $resultados]);
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);

        return $user;
    }
}
