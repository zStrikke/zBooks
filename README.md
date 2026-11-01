# zBooks

[![CI](https://github.com/zStrikke/zBooks/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/zStrikke/zBooks/actions/workflows/ci.yml)

Plataforma web inspirada en BookCrossing: registra tus libros, obtén un código único (ZBID), libéralos en lugares públicos y sigue su viaje por el mundo.

Proyecto personal, no comercial y de aprendizaje, desarrollado por iteraciones simulando un proyecto real.

## Estado

🟢 **[v0.1.0](https://github.com/zStrikke/zBooks/releases/tag/v0.1.0)** publicada: entorno Docker, calidad, CI y esqueleto de la API. Siguiente: **iteración 1** (v0.2.0, usuarios y autenticación).

## Stack

Laravel 13 · PHP 8.4 · PostgreSQL 16 + PostGIS 3.4 · Redis · Docker · Livewire 4 + Alpine.js · Tailwind CSS 4 · Filament · Leaflet · API REST v1 (Sanctum)

## Cómo arrancarlo

### Requisitos

- [Docker](https://docs.docker.com/get-docker/) con Docker Compose v2. En Windows, con WSL 2.
- Git y `make`. Sin `make`, cada comando del `Makefile` se puede copiar tal cual.
- Libres los puertos 80 (web), 5432 (PostgreSQL) y 8025 (Mailpit). Se cambian en el `.env` con `APP_PORT`, `FORWARD_DB_PORT` y `FORWARD_MAILPIT_UI_PORT`.

No hace falta PHP, Composer ni Node en tu equipo: todo corre en contenedores.

### Instalación

```bash
git clone https://github.com/zStrikke/zBooks.git
cd zBooks
make setup
```

`make setup` crea el `.env`, construye la imagen, instala las dependencias, genera la clave, crea las tablas y compila el front. Se puede repetir sin miedo.

Al terminar:

| Qué | Dónde |
|---|---|
| Web | http://localhost |
| Documentación de la API | http://localhost/docs/api |
| Correos enviados (Mailpit) | http://localhost:8025 |
| PostgreSQL | `localhost:5432`, usuario `zbooks`, contraseña `secret` (o `make psql`) |

> **Linux con un usuario distinto de 1000** (`id -u`): `make setup UID=$(id -u) GID=$(id -g)`, para que los ficheros que crean los contenedores sean tuyos.

### Comandos habituales

| Comando | Qué hace |
|---|---|
| `make up` / `make down` | Arranca o para el entorno |
| `make front` | Vite en modo desarrollo: los cambios de CSS y JS se ven al momento |
| `make assets` | Compila el front sin Vite en marcha |
| `make shell` | Terminal dentro del contenedor de la app |
| `make artisan c="route:list"` | Cualquier comando de Artisan |
| `make composer c="require foo/bar"` | Cualquier comando de Composer |
| `make migrate` / `make fresh` | Migraciones / recrear la base con datos de prueba |
| `make logs s=app` | Logs de un servicio |
| `make help` | Todos los comandos |

### Tests y calidad

```bash
make test                     # Pest contra PostGIS (base zbooks_testing)
make test c="--filter=health" # solo algunos tests
make check                    # Pint, Larastan, PHP Insights y tests: lo mismo que la CI
```

Los tests usan su propia base, `zbooks_testing`: nunca tocan los datos de desarrollo. Más detalles en [CONTRIBUTING.md](CONTRIBUTING.md#5-pull-requests).

### Si algo falla

- **`port is already allocated`:** otro programa usa ese puerto. Cámbialo en el `.env` y `make restart`.
- **`Vite manifest not found`:** falta compilar el front: `make assets` (o `make front`).
- **Permisos en `storage/` o `vendor/`:** en Linux, repite `make build` con tu `UID`/`GID` (ver arriba).
- **Empezar de cero:** `docker compose down -v` borra también las bases de datos; luego `make setup`.

## Documentación

| Documento | Descripción |
|---|---|
| [Análisis funcional v1.4](docs/analisis/zBooks_Analisis_Funcional_v1.4.pdf) | Requisitos, modelo de datos, stack y planificación |
| [Cambios desde el análisis v1.4](docs/analisis/cambios-desde-v1.4.md) | Lo que ha cambiado del análisis, con enlace a cada decisión |
| [Notas](docs/notas/) | Revisiones de la planificación y cierres de iteración |
| [Diario de desarrollo](docs/DIARIO.md) | Una entrada por sesión de trabajo |
| [ADRs](docs/adr/) | Decisiones de arquitectura |
| [Diagramas](docs/diagramas/) | UML y ER en Mermaid |
| [CHANGELOG](CHANGELOG.md) | Cambios por versión |
| [Backlog](https://github.com/zStrikke/zBooks/issues) | Historias, tareas y épicas por hito |

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

Cada funcionalidad se expone también en una API REST versionada (`/api/v1`), pensada para una futura app móvil. Documentación OpenAPI en `/docs/api`, generada con Scramble a partir del código (de momento solo en local).

- Errores en formato *Problem Details* (RFC 9457): `application/problem+json`.
- Límite de 60 peticiones por minuto por usuario o IP.
- `GET /api/v1/health` comprueba la base de datos y Redis.

## Cómo trabajamos

Guía completa en [CONTRIBUTING.md](CONTRIBUTING.md). Resumen:

- Historias de usuario como issues y tablero en GitHub Projects.
- Una rama por issue (`feature/…`, `fix/…`) y fusión mediante pull request.
- [Commits convencionales](https://www.conventionalcommits.org/es/) y [SemVer](https://semver.org/lang/es/).
