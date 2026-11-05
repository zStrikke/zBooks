<?php

use Laravel\Fortify\Features;

/*
| Fortify (ADR-0012): solo el motor. Las pantallas son nuestras (routes/web.php),
| por eso 'views' => false. Las funciones se activan según llegan sus issues.
*/
return [
    'guard' => 'web',
    'middleware' => ['web'],
    'auth_middleware' => 'auth',
    'passwords' => 'users',
    // Campo del formulario de login: admite email o nombre de usuario (#11, AuthenticateUser)
    'username' => 'login',
    'email' => 'email',
    'views' => false,
    'home' => '/',
    'prefix' => '',
    'domain' => null,
    'lowercase_usernames' => true,
    'limiters' => [
        // null: Fortify aplica su límite propio (5 intentos por minuto y por login + IP)
        // y lo muestra como error del formulario, no como una página 429
        'login' => null,
        // Reenvío del email: 1 por minuto; el enlace de verificación, 6 (AppServiceProvider)
        'verification' => 'verification',
    ],
    'paths' => [],
    'redirects' => [
        'email-verification' => '/',
        // Tras elegir una contraseña nueva, a entrar con ella
        'password-reset' => '/login',
    ],
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        // Cambio de contraseña desde Mi cuenta (#17): PUT /user/password
        Features::updatePasswords(),
    ],
];
