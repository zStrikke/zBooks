# Architecture Decision Records (ADR)

Un ADR documenta **una** decisión de arquitectura importante: el contexto, la decisión y sus consecuencias. Sirve para saber, meses después, *por qué* se hizo algo así.

## Reglas

- Un fichero por decisión: `NNNN-titulo-en-kebab-case.md`.
- Se escriben en el mismo PR que introduce la decisión.
- **No se editan una vez aceptados.** Si una decisión cambia, se crea un ADR nuevo que la *sustituye* y el antiguo pasa a estado `Sustituido por ADR-NNNN`.
- Plantilla: [`0000-plantilla.md`](0000-plantilla.md).

## Estados

`Propuesto` → `Aceptado` → (`Obsoleto` | `Sustituido por ADR-NNNN`)

## Índice

| ADR | Título | Estado | Fecha |
|---|---|---|---|
| [0001](0001-stack-laravel-livewire-docker.md) | Stack: Laravel + Livewire + Docker | Aceptado | 2026-10-30 |
| [0002](0002-filament-panel-administracion.md) | Panel de administración con Filament | Aceptado | 2026-10-30 |
| [0003](0003-diagramas-como-codigo-mermaid.md) | Diagramas como código con Mermaid | Aceptado | 2026-10-30 |
| [0004](0004-puntos-como-transacciones-inmutables.md) | Puntos como transacciones inmutables | Aceptado | 2026-10-30 |
| [0005](0005-mensajes-directos-como-canales.md) | Mensajes directos como canales privados | Aceptado | 2026-10-30 |
| [0006](0006-postgresql-postgis.md) | PostgreSQL + PostGIS en lugar de MySQL | Aceptado | 2026-10-30 |
| [0007](0007-api-rest-versionada.md) | API REST versionada junto a cada funcionalidad | Aceptado | 2026-10-30 |
| [0008](0008-autenticacion-sesion-y-sanctum.md) | Autenticación: sesión web + tokens Sanctum | Aceptado | 2026-10-30 |
| [0009](0009-laravel-13-php-84.md) | Laravel 13 y PHP 8.4 | Aceptado | 2026-10-31 |
| [0010](0010-privacidad-ciclo-del-libro.md) | Privacidad en el ciclo del libro: anonimato y franjas | Aceptado | 2026-10-31 |
| [0011](0011-idiomas-web-y-api.md) | Idiomas: código y API en inglés, web en el idioma del usuario | Aceptado | 2026-11-01 |
| [0012](0012-paquetes-autenticacion-permisos-geo.md) | Paquetes de la v0.2.0: Fortify, laravel-permission y laravel-magellan | Aceptado | 2026-11-01 |
| [0013](0013-zonas-de-usuario.md) | Zonas de usuario: radio o área, con la precisión que elija cada persona | Aceptado | 2026-11-01 |
