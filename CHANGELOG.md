# Changelog

Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y [SemVer](https://semver.org/lang/es/).

## [Unreleased]

### Added
- Guía del desarrollador: comandos, flujo de issue a PR, archivos de referencia y casos frecuentes (#51).

## [0.1.0] - 2026-11-01

Primera versión: entorno de desarrollo, calidad, CI y esqueleto de la API. Todavía sin funcionalidades para usuarios.

### Added
- Análisis funcional v1.5, con su fuente en Markdown, `make analisis` para generar el PDF y lista de cambios pendientes.
- CI fijada en Ubuntu 24.04, con acciones en Node 24 y Dependabot para mantenerlas al día (#46).
- README con cómo arrancar el proyecto: requisitos, `make setup`, comandos habituales, tests y problemas frecuentes. Nuevos atajos `make setup` y `make assets` (#9).
- La API responde siempre en inglés (errores, validación y documentación), sin depender del idioma de la web (#42).
- ADR-0011: código y API en inglés (`APP_LOCALE=en`); web en español, inglés o catalán según el usuario, con una sola URL por recurso.
- Esqueleto de la API v1: rutas por versión, errores en Problem Details (RFC 9457), límite de 60 peticiones/min, `GET /api/v1/health` y documentación OpenAPI con Scramble en `/docs/api` (#8).
- Integración continua con GitHub Actions: Pint, Larastan y PHP Insights en un job; Pest contra PostGIS y Redis en otro. Badge en el README (#7).
- Calidad desde el primer día: Pest 5 con tests de Feature contra PostGIS y tests de arquitectura, Pint, Larastan nivel 6 y PHP Insights (mínimo 90 por categoría); `composer check` y `make check` (#6).
- Livewire 4, Tailwind 4 con identidad visual propia, layout base y portada con buscador de ZBID (#5).
- Entorno Docker: PHP 8.4, nginx, PostgreSQL 16 + PostGIS 3.4, Redis, Mailpit y Vite; `Makefile` con atajos (#4).
- Esqueleto de Laravel 13 configurado para PostgreSQL, Redis y Mailpit (#4).
- ADR-0009: Laravel 13 y PHP 8.4.
- Plantillas de issues y pull request, `CONTRIBUTING.md` y backlog en GitHub (#3).
- ADRs 0001–0008, índice y plantilla (`docs/adr/`).
- Análisis funcional y planificación v1.4 (nomenclatura propia ZBID/ZBZ, PostgreSQL + PostGIS, API REST v1, autenticación web + Sanctum).
- Diario de desarrollo, README y estructura de documentación.
- Diagramas UML y ER en Mermaid (`docs/diagramas/`), incluida la arquitectura web + API y la secuencia de autenticación.

[Unreleased]: https://github.com/zStrikke/zBooks/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/zStrikke/zBooks/releases/tag/v0.1.0
