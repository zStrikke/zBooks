# Cierre de la iteración 0 (v0.1.0): análisis de issues, diario y estado

- **Fecha:** 2026-11-01
- **Alcance:** del arranque (diario #001, 30 oct) al cierre de la v0.1.0 (diario #015)

## 1. Qué se ha entregado

| Pieza | Estado |
|---|---|
| Entorno Docker (PHP 8.4, nginx, PostgreSQL 16 + PostGIS 3.4, Redis, Mailpit, Vite) | ✅ #4 |
| Laravel 13, Livewire 4, Tailwind 4, layout y portada con buscador de ZBID | ✅ #5 |
| Calidad: Pest, Pint, Larastan nivel 6, PHP Insights ≥ 90 | ✅ #6 |
| CI en GitHub Actions con los checks obligatorios en `master` | ✅ #7, #46 |
| API v1: versionado, Problem Details, límite, health, Scramble | ✅ #8 |
| API en inglés e idiomas decididos (ADR-0011) | ✅ #42 |
| README con `make setup` probado en un clon limpio | ✅ #9 |

**Cifras**
- 3 días de calendario y 15 sesiones de diario (7 de diseño y 8 de la iteración 0).
- 14 PRs contando el de cierre, todos revisados por el PO. 8 issues cerradas en la v0.1.0 (6 planificadas y 2 surgidas por el camino: #42 y #46).
- 11 ADRs, 1 nota de planificación y 9 diagramas.
- 25 tests (159 aserciones), PHP Insights a 100 en las cuatro categorías y Larastan sin errores.

## 2. Qué ha ido bien

- **Diseñar antes de programar.** La fase D (análisis, diagramas, ADRs y backlog) evitó rehacer el plan. Ninguna issue de la v0.1.0 cambió de alcance.
- **La calidad entró el primer día:** cada PR pasa los mismos checks en local y en la CI, y `master` los exige.
- **Las decisiones quedan escritas** y se revisan cuando hace falta: el ADR-0011 se rehízo durante la revisión del PR y el diario recoge por qué.
- **Los tests se comprueban rompiendo el código a propósito** (renderer, middleware, idioma), no solo viéndolos en verde.
- **La revisión del PO cambió decisiones de verdad** (privacidad, idiomas, staging): el proceso funciona como en un equipo real.

## 3. Qué había que corregir y cómo ha quedado

| Hallazgo | Resolución |
|---|---|
| Títulos desfasados: #5 decía «Laravel 12» y #6 no mencionaba PHP Insights | Títulos corregidos, con un comentario en cada issue que explica el cambio |
| La épica #27 seguía incluyendo la traducción, que ahora es la #43 (v0.2.0) | Tachada en la épica y comentario de cambio de alcance en #27 y #43 |
| El análisis v1.4 ya no refleja varias decisiones | [Análisis funcional v1.5](../analisis/zBooks_Analisis_Funcional_v1.5.pdf), con una primera página de cambios y referencias a cada decisión. La v1.4 se conserva |
| El diario #002 seguía «abierto» y con una tarea ya hecha sin marcar | Cerrado, con una nota que explica la corrección |
| Workflow del tablero que manda las issues nuevas a Todo | Pendiente del PO (ver abajo) |
| Token de Docker Hub compartido en el chat | Pendiente del PO: revocarlo |

**El tablero.** El aviso «Maximum number of auto-add workflows reached» sale al crear un segundo flujo *Auto-add to project*: el plan gratuito permite uno. No hace falta otro: basta con editar el flujo ya existente **Item added to project** y cambiar su estado de Todo a **Backlog**.

## 4. Decisiones pendientes

De la [revisión de la planificación](2026-10-31-revision-planificacion.md):

- **Métrica principal del producto.** Sin decidir; se propone en la sesión de refinamiento de la v0.2.0, aunque no se medirá hasta la v0.5.0.
- **Mapas y geocodificación.** Para desarrollo ya está decidido en la #18: Leaflet con los mapas de OpenStreetMap y el buscador Nominatim, con caché y respetando sus políticas de uso. El proveedor de producción se decide antes de la épica #28.
- **RGPD.** Lo habitual del sector, adelantado a la v0.2.0: aviso legal, política de privacidad, aceptación en el registro y solo cookies técnicas, así que no hace falta banner. Issue #48. Exportar y anonimizar la cuenta siguen en la #28.
- **Observabilidad, por escalones:**
  1. Logs diarios que se guardan 7 días y errores a Slack (#49, épica #28). Laravel borra solo los ficheros viejos con `LOG_DAILY_DAYS`, sin cron.
  2. Seguimiento de errores (Sentry o Flare).
  3. Monitor de disponibilidad y métricas.

## 5. Riesgos para la v0.2.0

| Riesgo | Mitigación |
|---|---|
| Iteración demasiado grande: 10 historias y tareas, más #43 y #48 | Refinar antes de empezar: decidir qué es *must* y si #14, #17, #19 o #43 pasan a la v0.3.0 |
| Paquetes sin decidir (kit de autenticación, permisos, Filament) | Un ADR corto antes de la primera historia |
| Datos personales sin política de privacidad | #48 antes que el registro (#10) |

## 6. Siguientes pasos

1. Cerrar la v0.1.0: etiqueta, release y CHANGELOG.
2. Sesión de refinamiento de la v0.2.0: alcance, orden, paquetes y métrica principal.
3. Arrancar la iteración 1 con la versión `0.2.0-dev`.
