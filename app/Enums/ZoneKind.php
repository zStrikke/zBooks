<?php

namespace App\Enums;

/**
 * Tipo de zona de un miembro (ADR-0013): la habitual (#18) o una temporal (#19).
 */
enum ZoneKind: string
{
    case Home = 'home';
    case Temporary = 'temporary';
}
