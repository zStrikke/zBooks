<?php

return [

    /*
    | Usuario admin de desarrollo (lo crea AdminUserSeeder, nunca en producción).
    */
    'dev_admin' => [
        'username' => 'admin',
        'email' => env('DEV_ADMIN_EMAIL', 'admin@zbooks.test'),
        'password' => env('DEV_ADMIN_PASSWORD', 'password'),
    ],

    /*
    | Nombres de usuario (#10): no se pueden registrar, sin distinguir mayúsculas.
    | Rutas, marcas y nombres que podrían confundirse con el equipo.
    */
    'reserved_usernames' => [
        'admin', 'administrator', 'api', 'app', 'docs', 'help', 'login', 'logout',
        'me', 'moderator', 'null', 'privacy', 'register', 'root', 'settings',
        'soporte', 'support', 'system', 'u', 'user', 'zbooks', 'zbid',
    ],

    /*
    | Mapa de Mi zona (#18). Las teselas de OpenStreetMap valen para desarrollo
    | y la demo; su política no admite tráfico de producción (ADR-0014).
    */
    'map' => [
        'tiles' => env('MAP_TILES_URL', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'),
        'attribution' => env('MAP_TILES_ATTRIBUTION', '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'),
    ],

    /*
    | Buscador de direcciones (#18, App\Geo\Nominatim). Su política pide un
    | User-Agent que identifique la aplicación.
    */
    'nominatim' => [
        'url' => env('NOMINATIM_URL', 'https://nominatim.openstreetmap.org/search'),
        'user_agent' => env('NOMINATIM_USER_AGENT', 'zBooks/0.2 (+https://github.com/zStrikke/zBooks)'),
    ],
];
