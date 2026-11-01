<?php

use App\Http\Controllers\Api\V1\HealthController;
use Illuminate\Support\Facades\Route;

// Prefijo /api/v1 y nombres api.v1.* (definidos en routes/api.php)

Route::get('health', HealthController::class)->name('health');
