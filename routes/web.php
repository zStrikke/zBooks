<?php

use App\Http\Controllers\LegalController;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyEmail;
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

// Cuentas (#10). Fortify pone el POST /register y el enlace de verificación;
// las pantallas son nuestras (ADR-0012).
Route::get('/register', Register::class)->middleware('guest')->name('register');
Route::get('/email/verify', VerifyEmail::class)->middleware('auth')->name('verification.notice');
