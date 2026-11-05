<?php

use Laravel\Fortify\Features;

/*
| Fortify (ADR-0012): solo el motor. Las pantallas son componentes Livewire
| propios (routes/web.php), por eso 'views' => false.
| Las funciones se activan según llegan sus issues: login #11, contraseña #13.
*/
return [
    'guard' => 'web',
    'middleware' => ['web'],
    'auth_middleware' => 'auth',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'views' => false,
    'home' => '/',
    'prefix' => '',
    'domain' => null,
    'lowercase_usernames' => true,
    'limiters' => [
        'login' => null,
        // Reenvío del email: 1 por minuto; el enlace de verificación, 6 (AppServiceProvider)
        'verification' => 'verification',
    ],
    'paths' => [],
    'redirects' => [
        'email-verification' => '/',
    ],
    'features' => [
        Features::registration(),
        Features::emailVerification(),
    ],
];
