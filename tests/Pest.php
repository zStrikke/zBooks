<?php

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Configuración de Pest
|--------------------------------------------------------------------------
|
| Los tests de Feature usan la base de datos real de tests (PostgreSQL +
| PostGIS, `zbooks_testing`), nunca SQLite: las consultas espaciales y la
| búsqueda full-text solo existen en PostgreSQL (ADR-0006).
|
| LazilyRefreshDatabase migra la base una vez y envuelve cada test en una
| transacción que se deshace al terminar. Si un test no toca la base, no
| hace nada.
|
*/

pest()->extend(TestCase::class)
    ->use(LazilyRefreshDatabase::class)
    ->in('Feature');
