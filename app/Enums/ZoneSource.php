<?php

namespace App\Enums;

/**
 * De dónde salió el punto de la zona (ADR-0013).
 */
enum ZoneSource: string
{
    case Geolocation = 'geolocation';
    case Map = 'map';
    case Area = 'area';
}
