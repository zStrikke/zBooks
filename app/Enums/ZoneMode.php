<?php

namespace App\Enums;

/**
 * Cómo se define la zona (ADR-0013): un punto con radio (#18) o un barrio o
 * ciudad del catálogo (#54, v0.3.0).
 */
enum ZoneMode: string
{
    case Radius = 'radius';
    case Area = 'area';
}
