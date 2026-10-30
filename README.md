# zBooks

Plataforma web inspirada en BookCrossing: registra tus libros, obtén un código único (ZBID), libéralos en lugares públicos y sigue su viaje por el mundo.

Proyecto personal, no comercial y de aprendizaje, desarrollado por iteraciones simulando un proyecto real.

## Estado

🟡 **Fase de diseño** (pre-0.1.0). Todavía no hay código.

## Stack

Laravel 12 · PostgreSQL 16 + PostGIS · Docker · Livewire 3 + Alpine.js · Tailwind CSS · Filament · Redis · Leaflet · API REST v1 (Sanctum)

## Documentación

| Documento | Descripción |
|---|---|
| [Análisis funcional v1.4](docs/analisis/zBooks_Analisis_Funcional_v1.4.pdf) | Requisitos, modelo de datos, stack y planificación |
| [Diario de desarrollo](docs/DIARIO.md) | Una entrada por sesión de trabajo |
| [ADRs](docs/adr/) | Decisiones de arquitectura |
| [Diagramas](docs/diagramas/) | UML y ER en Mermaid |
| [CHANGELOG](CHANGELOG.md) | Cambios por versión |

## Hoja de ruta

| Versión | Contenido |
|---|---|
| 0.1.0 | Entorno Docker + CI |
| 0.2.0 | Usuarios, autenticación (web + API) y zona de referencia |
| 0.3.0 | Libros, ZBID y etiquetas |
| 0.4.0 | Liberar, capturar y diario |
| **0.5.0** | **MVP:** recorrido con PostGIS, búsqueda, zonas, notificaciones, API documentada |
| 0.6.0 – 0.10.0 | Admin, reseñas, gamificación, blog, canales, eventos, importación Goodreads |
| **1.0.0** | Producción |

## API

Cada funcionalidad se expone también en una API REST versionada (`/api/v1`), pensada para una futura app móvil. Documentación OpenAPI en `/docs/api` (disponible desde la v0.1.0).

## Cómo trabajamos

- Historias de usuario como issues y tablero en GitHub Projects.
- Una rama por issue (`feature/…`, `fix/…`) y fusión mediante pull request.
- [Commits convencionales](https://www.conventionalcommits.org/es/) y [SemVer](https://semver.org/lang/es/).
