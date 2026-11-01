<?php

/*
|--------------------------------------------------------------------------
| Textos legales (RGPD y LSSI)
|--------------------------------------------------------------------------
|
| Datos que aparecen en el aviso legal y en la política de privacidad.
| Mientras no se rellenen, se muestran como huecos entre corchetes.
|
*/

return [

    // Versión vigente de la política de privacidad: la fecha en que se publicó.
    // Si el texto cambia de forma relevante, se sube y se pide aceptarla de nuevo.
    'privacy_version' => '2026-11-01',

    // Responsable del tratamiento y titular del sitio
    'controller' => [
        'name' => env('LEGAL_CONTROLLER_NAME', '[nombre]'),
        'email' => env('LEGAL_CONTACT_EMAIL', '[email de contacto]'),
        'country' => env('LEGAL_COUNTRY', '[país]'),
    ],

];
