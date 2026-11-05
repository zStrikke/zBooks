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
];
