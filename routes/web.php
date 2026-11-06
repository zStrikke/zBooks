<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\EmailVerificationNoticeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Búsqueda por ZBID: de momento solo informa; la funcionalidad llega en la v0.4.0.
Route::get('/zbid', function (Request $request) {
    $codigo = mb_strtoupper(trim((string) $request->query('codigo', '')));

    return view('zbid.buscar', ['codigo' => mb_substr($codigo, 0, 20)]);
})->name('zbid.buscar');

// Textos legales (RGPD y LSSI, issue #48)
Route::get('/legal-notice', [LegalController::class, 'notice'])->name('legal.notice');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');

// Cuentas (#10, #11). Fortify pone los POST (registro, login, logout, contraseña)
// y el enlace de verificación; las pantallas son nuestras (ADR-0012).
Route::get('/email/verify', EmailVerificationNoticeController::class)->middleware('auth')->name('verification.notice');

Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
});

// Perfil (#16). No es público (#93): con sesión, y solo el propio miembro o sus amigos.
// El patrón es el de App\Rules\Username.
Route::get('/u/{username}', [ProfileController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->where('username', '[A-Za-z0-9_]{1,20}')
    ->name('profile.show');

// Mi cuenta (#17). La contraseña va al PUT /user/password de Fortify.
Route::middleware(['auth', 'verified'])->prefix('account')->name('account')->group(function () {
    Route::get('/', [AccountController::class, 'show']);
    Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('.profile.update');
    Route::delete('/avatar', [AccountController::class, 'destroyAvatar'])->name('.avatar.destroy');

    // Mi zona (#18)
    Route::put('/zone', [ZoneController::class, 'update'])->name('.zone.update');
    Route::delete('/zone', [ZoneController::class, 'destroy'])->name('.zone.destroy');
    Route::get('/zone/search', [ZoneController::class, 'search'])->middleware('throttle:30,1')->name('.zone.search');
});
