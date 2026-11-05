<?php

use App\Http\Controllers\Auth\EmailVerificationNoticeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProfileController;
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

// Perfil público (#16). El patrón es el de App\Rules\Username.
Route::get('/u/{username}', [ProfileController::class, 'show'])
    ->where('username', '[A-Za-z0-9_]{1,20}')
    ->name('profile.show');
