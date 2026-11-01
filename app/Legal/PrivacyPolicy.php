<?php

namespace App\Legal;

use App\Models\User;

/**
 * Consentimiento de la política de privacidad (RGPD).
 *
 * Reúne en un solo sitio la versión vigente, la regla de validación del
 * formulario de registro (web y API) y cómo se guarda la aceptación.
 */
class PrivacyPolicy
{
    /** Campo del formulario y de la API con la casilla de aceptación */
    public const FIELD = 'privacy_policy';

    public static function currentVersion(): string
    {
        return (string) config('legal.privacy_version');
    }

    /**
     * Reglas para añadir a la validación del registro.
     *
     * @return array<string, list<string>>
     */
    public static function rules(): array
    {
        return [self::FIELD => ['accepted']];
    }

    public static function recordAcceptance(User $user): void
    {
        $user->forceFill([
            'privacy_accepted_at' => now(),
            'privacy_version' => self::currentVersion(),
        ])->save();
    }

    /** ¿Aceptó la versión vigente? Si la política cambia, deja de ser cierto. */
    public static function hasAccepted(User $user): bool
    {
        return $user->privacy_accepted_at !== null
            && $user->privacy_version === self::currentVersion();
    }
}
