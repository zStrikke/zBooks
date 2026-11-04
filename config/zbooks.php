<?php

return [

    /*
    | Usuario admin de desarrollo (lo crea AdminUserSeeder, nunca en producción).
    */
    'dev_admin' => [
        'name' => 'Admin',
        'email' => env('DEV_ADMIN_EMAIL', 'admin@zbooks.test'),
        'password' => env('DEV_ADMIN_PASSWORD', 'password'),
    ],

];
