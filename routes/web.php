<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Búsqueda por ZBID: de momento solo informa; la funcionalidad llega en la v0.4.0.
Route::get('/zbid', function (Request $request) {
    $codigo = mb_strtoupper(trim((string) $request->query('codigo', '')));

    return view('zbid.buscar', ['codigo' => mb_substr($codigo, 0, 20)]);
})->name('zbid.buscar');
