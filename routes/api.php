<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
|
| Cada versión tiene su propio fichero (ADR-0007). Un cambio incompatible
| crea `routes/api/v2.php`; la v1 se mantiene un tiempo con la cabecera
| `Sunset`.
|
*/

Route::prefix('v1')
    ->name('api.v1.')
    ->group(base_path('routes/api/v1.php'));
