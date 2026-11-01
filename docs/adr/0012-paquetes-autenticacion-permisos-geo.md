# ADR-0012: Paquetes de la v0.2.0: Fortify, laravel-permission y laravel-magellan

- **Estado:** Aceptado
- **Fecha:** 2026-11-01
- **Decisores:** PO, Claude

## Contexto

La v0.2.0 trae cuentas (web y API), roles y la zona del usuario. El análisis v1.4 nombraba Breeze para la web, pero la autenticación tiene que servir a la vez a la web (sesión) y a la API (tokens Sanctum) con la misma lógica (ADR-0008), y las pantallas deben llevar el diseño de zBooks.

## Opciones consideradas

**Autenticación web**
1. **Kit de inicio oficial** (sucesor de Breeze): trae pantallas y componentes con su propio diseño, que habría que rehacer.
2. **Fortify:** el motor de autenticación oficial de Laravel, sin pantallas. Registro, login, verificación de email y recuperación de contraseña como acciones y rutas; las vistas las ponemos nosotros.
3. **Hecho a mano:** control total, pero reescribiríamos lo que Fortify ya resuelve y prueba.

**Roles y permisos:** `spatie/laravel-permission` (ya en el análisis) o roles propios en una columna.

**Geodatos en Eloquent:** `clickbar/laravel-magellan` (ya en el análisis) o consultas SQL a mano.

## Decisión

- **Fortify** para la autenticación web, con pantallas propias en Livewire.
  - Sus acciones (`CreateNewUser`, `ResetUserPassword`…) son también las que usa la API: una sola lógica para los dos accesos.
  - Si Fortify no encajara con Laravel 13 al instalarlo, se reabre este ADR.
- **Sanctum** para los tokens de la API (ya decidido en el ADR-0008).
- **`spatie/laravel-permission`** para roles: `member`, `moderator` y `admin`. Las Policies comprueban roles o permisos, nunca el email ni el ID.
- **`clickbar/laravel-magellan`** para PostGIS: tipos `Point` y `MultiPolygon` en los modelos y funciones (`ST_DWithin`, `ST_Covers`…) en las consultas.

## Consecuencias

**Positivas**
- Las pantallas de acceso llevan el diseño de zBooks desde el principio, sin desmontar un kit.
- Web y API comparten las acciones de registro y contraseña.
- Roles y geodatos con paquetes muy usados y probados.

**Negativas / riesgos**
- Fortify no trae vistas: hay que hacer las de registro, login, verificación y recuperación (ya previstas en el diseño de pantallas).
- Tres dependencias nuevas que mantener al día.

**Seguimiento**
- Filament (v0.6.0) usará los mismos roles de `laravel-permission`.
- Autenticación en dos pasos: Fortify la trae; se valorará más adelante.

## Referencias

- ADR-0007, ADR-0008 · Issues #10–#13, #15, #18
