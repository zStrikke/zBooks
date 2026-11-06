# Changelog

Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/) y [SemVer](https://semver.org/lang/es/).

## [Unreleased]

### Added
- Web traducible: todos los textos de las vistas se escriben en inglés con `__()` y se traducen en `lang/es.json` (cabecera, pie, portada y búsqueda de ZBID incluidos); traducciones de paginación; columna `users.locale` (por defecto `es`) para el selector de idioma de la #53; un test falla si un texto no tiene traducción al español (#43).
- Mi zona (sección de Mi cuenta): zona habitual con un punto y un radio de 0,5 a 25 km (2,5 por defecto). El punto se elige con la ubicación del navegador, tocando el mapa (Leaflet + OpenStreetMap) o con un buscador de direcciones que busca mientras se escribe (Nominatim, desde el servidor, con caché y como mucho una petición por segundo), con un indicador tipo radar mientras localiza o busca. Se guarda **redondeado a ~250 m**, nunca la dirección exacta, y no aparece en el perfil público. Una sola zona habitual por miembro, garantizada por la base de datos (#18).
- Primera tabla con PostGIS: `user_zones` (`geography(Point, 4326)`, índice espacial) con `clickbar/laravel-magellan` (#18).
- `make post-pull`: tras un `git pull`, ejecuta en orden los comandos que hacen falta (imagen de Docker, Composer, front, migraciones, worker de la cola) y resume qué hizo; `make fresh` y el `.env` quedan como aviso y `ver=1` solo lista. Sección «Después de un `git pull`» en la guía del desarrollador (#78, #86).
- Mi cuenta (`/account`, con sesión y email verificado): cambiar o quitar el avatar (JPG, PNG o WebP hasta 2 MB, recortado cuadrado a 256 px y guardado como WebP), la bio (hasta 500 caracteres, con contador) y la contraseña (pide la actual). Vista previa de la ficha de socio, con pestañas Móvil y Escritorio, que cambia mientras se escribe. Enlace «Mi cuenta» en la cabecera; Mi zona queda reservada para la #18 (#17).
- Perfil en `/u/{username}`. Cualquier otra forma del username redirige (301) a la original. Muestra una ficha de socio (tapa de tela, número de socio, sello de alta y el username firmado) con avatar (iniciales si no hay), bio, fecha de alta, las cifras de libros registrados, liberados y capturados (0 hasta la v0.3.0) y el espacio reservado para el blog. Nunca el email ni la zona. La cabecera enlaza al perfil propio (#16).
- Login en la web (`/login`) con email o nombre de usuario, sin distinguir mayúsculas, y «Mantener la sesión iniciada»; bloqueo tras 5 intentos fallidos en un minuto; logout que invalida la sesión. «Entrar» y «Salir» en la cabecera (#11).
- «He olvidado mi contraseña» (`/forgot-password`) y nueva contraseña desde el enlace del email (`/reset-password/{token}`), con las mismas reglas que el registro. El email va en cola y en el idioma de la web, y la respuesta no revela si un email tiene cuenta (#11).
- Registro en la web (`/register`) con Fortify y pantallas propias según las maquetas: username (3–20 letras, números o `_`, único sin distinguir mayúsculas y con nombres reservados), email, contraseña (`Password::defaults()`, con comprobación de filtraciones en producción) y aceptación de la privacidad. La cuenta nueva recibe el rol `member` y la sesión queda iniciada (#10).
- Verificación del email: enlace firmado que caduca en 60 minutos, enviado en cola y en el idioma de la web; aviso «Revisa tu correo» (`/email/verify`) con reenvío limitado a uno por minuto; banda de aviso mientras no se verifique y confirmación al verificar. El middleware `verified` lleva al aviso desde las rutas que escriben (#10).
- Servicio `queue` en Docker Compose, que procesa la cola (emails), y `make queue-restart` (#10).
- Primeras traducciones al español (`lang/es.json` y `lang/es/validation.php`) y middleware `SetWebLocale`, que fija el español en la web; la API sigue en inglés (#10, adelanta parte de #43).
- Roles base `member`, `moderator` y `admin` con spatie/laravel-permission, enum `App\Enums\Role`, `RoleSeeder` idempotente y un admin de desarrollo que nunca se crea en producción (`DEV_ADMIN_*` en `.env.example`) (#15, #65).
- `make check` resume cada comprobación con ✓/✗, su tiempo y cómo arreglar lo que falla; el hook de pre-push muestra lo mismo (#63).
- Aviso legal y política de privacidad (`/legal-notice`, `/privacy`), escritos en Markdown por idioma y enlazados desde el pie, con los datos del responsable en `config/legal.php`; consentimiento versionado en `users` y `App\Legal\PrivacyPolicy` para el registro (#48).
- Maquetas de la v0.2.0 (registro, login, verificación de email y perfil público, en móvil y escritorio) en `docs/diseno/`.
- Ayuda sobre issues, tablero, etiquetas y flujos (#56).
- ADR-0012 (Fortify, laravel-permission y laravel-magellan) y ADR-0013 (zonas de usuario por radio o por área).
- Guía del desarrollador: comandos, flujo de issue a PR, archivos de referencia y casos frecuentes (#51).

### Changed
- Cabecera: con sesión, el avatar abre un menú de usuario (en escritorio, un desplegable; en móvil, un panel lateral desde la izquierda que también lleva la navegación y el buscador de ZBID) (saludo, libros liberados y capturados, estado de Mi zona, Mi perfil, Mi cuenta, Cerrar sesión). Lo que aún no existe sale desactivado con su versión: Mis libros, Mis capturas, Amigos y Dar feedback. Al lado, una campana abre «Tus notificaciones» con los tipos que llegarán, desactivados: mensajes privados, capturas de tus libros, libros liberados cerca, eventos y solicitudes de amistad (#95).
- El perfil (`/u/{username}`) deja de ser público: pide sesión y solo lo ve el propio miembro (y sus amigos, cuando llegue la #37). Los demás reciben un 404, como si no existiera (#93).
- Portada: «Cómo funciona» muestra el ciclo del libro con una línea que vuelve del último paso al primero, y la línea discontinua lleva menos guiones (#91).
- La API v1 se aplaza a su propio hito (v0.11.0) para priorizar la web; la plantilla de PR y el ADR-0007 lo recogen (#74).
- Los roles base se crean con una migración, no con un seeder: existen en todos los entornos tras `migrate`. Se quita `RoleSeeder` (#71).
- Las pantallas de cuenta (registro y «Revisa tu correo») pasan de Livewire a Blade y Alpine, y envían a las rutas de Fortify. El límite de reenvío del email es el mismo para el registro, el aviso y Fortify, y sin página de error (#69).
- `users.name` pasa a `users.username`, con índice único sobre `lower(username)` (#10).
- Los tests fijan `APP_LOCALE=en` en `phpunit.xml` y ya no dependen del `.env` local (#60).
- Diagrama ER: `user_zones` con modo radio o área y nueva tabla `areas`.

### Security
- Symfony 8.1.11 y contratos 3.8.1 por avisos de seguridad; `composer audit` limpio (#60).

### Fixed
- Las fotos de perfil daban 403 en los entornos montados antes de la #17: faltaba el enlace `public/storage`. `make post-pull` lo comprueba siempre y lo crea si falta (#89).
- «Así te ven» (Mi cuenta) muestra una sola vista previa de la ficha, a tamaño real, en lugar de las pestañas Móvil y Escritorio: la ficha es casi igual en las dos y la columna del blog de la pestaña Escritorio parecía un fallo (#80).

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
