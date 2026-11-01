# Diario de desarrollo — zBooks

> Equipo: Yo (product owner + dev) y Claude (dev + arquitecto).
> Una entrada por sesión de trabajo. Las decisiones importantes se registran además como ADR en `docs/adr/`.

---

## Plantilla de entrada

```
## [AAAA-MM-DD] #NNN — Título corto
**Iteración:** X  ·  **Duración:** ~h

### Objetivo de la sesión
### Hecho
### Decisiones (→ ADR si aplica)
### Problemas / bloqueos
### Aprendido
### Siguiente paso
```

---

## [2026-10-30] #001 — Arranque y análisis funcional
**Iteración:** Pre-0 (análisis)  ·  **Duración:** ~1 h

### Objetivo de la sesión
Entender qué es BookCrossing y producir un primer análisis funcional con planificación por iteraciones.

### Hecho
- Investigación de BookCrossing.com: objetivo (biblioteca global) y funcionalidades (BCID, liberar, capturar, diario, OBCZ, rings).
- Documento **Análisis funcional y planificación v1.0** (PDF): 29 RF, RNF, casos de uso, modelo de datos, stack, 9 iteraciones (~14 semanas).

### Decisiones
- Stack base: Laravel 12, MySQL 8, Docker, Livewire 3 + Alpine.js, Tailwind.
- No mezclar Vue y Livewire (Vue + Inertia solo si se quisiera SPA).
- Añadidos aceptados: Redis/Horizon, Leaflet + OSM, Open Library, Meilisearch, Pest/Larastan/Pint, GitHub Actions.

### Siguiente paso
Debatir y afinar funcionalidades antes de empezar a programar.

---

## [2026-10-30] #002 — Debate de funcionalidades
**Iteración:** Pre-0 (análisis)  ·  **Duración:** varias conversaciones el mismo día

> Corregido en el cierre de la iteración 0: la entrada se escribió mientras el debate seguía abierto y no se cerró al terminar.

### Objetivo de la sesión
Ampliar el alcance con nuevas funcionalidades y simular un proceso de proyecto real.

### Propuestas del PO
- Sistema de gamificación.
- Panel de administración (preferencia inicial: AdminLTE).
- Visualización del recorrido de un libro.
- Diagramas UML y ER de la base de datos.
- Funcionalidades de comunidad (foros, blog…).

### Decisiones tomadas
- [x] **Admin: Filament** (mismo stack Tailwind/Livewire) → ADR-0002.
- [x] **Gamificación completa:** puntos, insignias, niveles y rankings. Los puntos fuertes llegan cuando otro captura tu libro; habrá límites diarios contra trampas.
- [x] **Reseñas con puntos.** Reseña ≠ entrada de diario: la reseña opina sobre la *obra* y es agregada; el diario registra el *viaje del ejemplar*.
- [x] **Comunidad:**
  - Canales tipo Slack para debatir.
  - Blog personal como espacio principal del usuario.
  - Eventos/quedadas abiertos o solo por invitación.
  - Reseñas y valoraciones.
  - Importación desde Goodreads.

- [x] **Canales:**
  - Los privados los crea cualquier miembro. En el futuro, solo con reputación mínima.
  - Los públicos solo los crean moderadores/admin.
  - Los mensajes directos son canales privados de dos personas.
- [x] **Blog:** espacio propio, sin «me gusta» ni comentarios por ahora (a estudiar en el futuro).
- [x] **Eventos:** con límite de plazas y lista de espera; se pueden vincular a una zona ZBZ.
- [x] **Nuevo — zona de referencia del usuario:** se puede cambiar de forma permanente (mudanza) o temporal con fecha de vuelta (viaje).
- [x] **Diagramas:** Mermaid, en una fase de diseño previa a programar.
- [x] **Proceso:**
  - Historias de usuario, issues + tablero, ramas/PR, commits convencionales.
  - SemVer + CHANGELOG, ADRs, retrospectivas.
  - Sin staging ni estimación.
- [x] Replanificación: Fase D + 11 iteraciones, ~19 semanas. MVP = v0.5.0 (semana 9).

### Hecho
- Documento **Análisis funcional v1.1** (PDF, 14 págs.): 46 RF, gamificación, comunidad, diagramas, proceso, backlog futuro.

### Pendiente
- [x] Confirmar cuenta de GitHub para crear repo y tablero (hecho en #003).

### Retro rápida
- ✅ Debatir antes de programar ha evitado rehacer el plan.
- 🔧 El alcance ha crecido bastante: vigilar no tocar nada post-MVP antes de la v0.5.0.

### Siguiente paso
Fase D: diagramas UML/ER en Mermaid + ADRs 0001–0005 + backlog de historias.

---

## [2026-10-30] #003 — Nombre definitivo y repositorio
**Iteración:** Pre-0 (análisis)  ·  **Duración:** ~30 min

### Objetivo de la sesión
Crear el repositorio y dejar la documentación versionada.

### Hecho
- El proyecto pasa a llamarse **zBooks**.
- Repositorio público: https://github.com/zStrikke/zBooks
- Estructura `docs/` con análisis funcional v1.2, diario, `adr/` y `diagramas/`.
- README y CHANGELOG iniciales.

### Decisiones
- Repositorio público (portafolio).
- La documentación vive en el repo; el diario es `docs/DIARIO.md`.

### Siguiente paso
Fase D: diagramas UML/ER en Mermaid + ADRs 0001–0005 + backlog de historias como issues.

---

## [2026-10-30] #004 — Fase D: diagramas
**Iteración:** Fase D (diseño)  ·  **Duración:** ~1 h

### Objetivo de la sesión
Diagramas UML y ER del sistema en Mermaid, entregados mediante el primer pull request.

### Hecho
- Rama principal renombrada a `master` (falta cambiar la rama por defecto en GitHub).
- 7 diagramas en `docs/diagramas/`: casos de uso, dominio, estados del ejemplar, secuencia de captura, secuencia de puntos, ER y despliegue.
- Sintaxis validada con el parser oficial de Mermaid.

### Decisiones
- Estados del ejemplar: `ON_SHELF`, `RELEASED`, `CAUGHT` (solo captura anónima), `LOST`, `ARCHIVED`. Una captura de un miembro devuelve el libro a `ON_SHELF` con nuevo poseedor.
- Puntos idempotentes mediante `idempotency_key` única.
- Mensajes directos = canal `direct` de dos miembros.

### Problemas / bloqueos
- El cambio de la rama por defecto en GitHub no se puede hacer desde la sesión de Claude; lo hace el PO.

### Aprendido
- Mermaid no tiene diagrama de casos de uso nativo; se simula con `flowchart`.

### Siguiente paso
Revisión del PR por el PO → ADRs 0001–0005.

---

## [2026-10-30] #005 — PostGIS, API v1 y autenticación
**Iteración:** Fase D (diseño)  ·  **Duración:** ~1 h

### Objetivo de la sesión
Preparar el diseño para una futura app móvil y aprovechar la experiencia previa del PO con datos geoespaciales.

### Hecho
- Rama por defecto en GitHub cambiada a `master` (PO).
- Diagramas actualizados en el PR #1:
  - ER con `geography(Point, 4326)`, índices GiST/GIN y tabla de tokens.
  - Despliegue con `postgis/postgis` y sin Meilisearch.
  - Nuevos: **08 arquitectura web + API** y **09 secuencia de autenticación**.
- Análisis funcional **v1.3**: RF-47 a RF-51 (API), RNF-10/11, planificación de ~22 semanas.

### Decisiones (→ ADR)
- **ADR-0006:** PostgreSQL 16 + PostGIS en lugar de MySQL.
  - Cercanía con `ST_DWithin`.
  - Rutas con `ST_MakeLine`.
  - Km con `ST_Length`.
  - Salida en GeoJSON.
- **ADR-0007:** API REST `/api/v1`, construida **junto a cada funcionalidad** (opción A).
  - Web y API comparten Actions.
  - Errores RFC 9457.
  - Documentación OpenAPI con Scramble.
- **ADR-0008:** autenticación.
  - Sesión en la web (Breeze).
  - Tokens Sanctum por dispositivo en la API.
  - Verificación de email obligatoria para escribir.
- La búsqueda usa el full-text de PostgreSQL; Meilisearch pasa al backlog.
- Relatos de casos típicos en los diagramas 03, 04 y 05 (petición del PO en la revisión).
- **Nomenclatura propia:** los términos heredados de BookCrossing pasan a ser de zBooks: `BCID` → **`ZBID`** (zBooks ID) y `OBCZ` → **`ZBZ`** (Zona zBooks). Las menciones a BookCrossing solo quedan donde se habla de la web original como inspiración.
- **Timestamps y soft deletes** definidos por tabla en el ER. Los usuarios no se borran lógicamente: se anonimizan (RGPD).
- **Estado «perdido»:** el registrador original puede marcar su libro como perdido tras 12 meses sin actividad. Desde la estantería, el poseedor puede hacerlo en cualquier momento.

### Aprendido
- Sanctum no hace falta para una web Livewire en el mismo dominio: basta la sesión. Sanctum aporta los tokens para clientes externos.

### Retro rápida
- ✅ Cambiar la base de datos antes de escribir código ha salido gratis.
- 🔧 La API añade ~3 semanas: vigilar que la lógica viva solo en Actions para no duplicar trabajo.

### Siguiente paso
Revisión y merge del PR #1 → PR con ADRs 0001–0008.

---

## [2026-10-31] #006 — Fase D: ADRs
**Iteración:** Fase D (diseño)  ·  **Duración:** ~45 min

### Objetivo de la sesión
Documentar como ADRs las decisiones de arquitectura tomadas hasta ahora.

### Hecho
- PR #1 (diagramas) aprobado y fusionado por el PO.
- `docs/adr/`: índice con reglas y estados, plantilla y **ADRs 0001–0008**.

### Decisiones
- Formato propio inspirado en MADR: estado, contexto, opciones, decisión, consecuencias, seguimiento y referencias.
- Los ADRs aceptados no se editan; un cambio de decisión crea un ADR nuevo que sustituye al anterior.

### Aprendido
- Un ADR también recoge las **opciones descartadas**: explica tanto por qué sí como por qué no.

### Siguiente paso
Revisión del PR de ADRs → backlog de historias de usuario como issues en GitHub.

---

## [2026-10-31] #007 — Fase D: backlog y forma de trabajar
**Iteración:** Fase D (diseño)  ·  **Duración:** ~45 min

### Objetivo de la sesión
Convertir el análisis en un backlog real en GitHub y fijar el flujo de trabajo.

### Hecho
- PR #2 (ADRs) aprobado y fusionado.
- **Etiquetas:**
  - Tipo: historia, épica, técnica, docs.
  - Prioridad MoSCoW.
  - 11 áreas.
  - `necesita refinamiento`.
- **12 hitos**, uno por versión: de Fase D a v1.0.0.
- **26 issues:**
  - #3: plantillas y guía de contribución.
  - #4–#9: v0.1.0, tareas técnicas.
  - #10–#19: v0.2.0, historias con criterios de aceptación.
  - #20–#28: épicas de v0.3.0 a v1.0.0, pendientes de refinar.
- Plantillas de issue (historia, técnica, bug), plantilla de PR con la DoD y `CONTRIBUTING.md`.
- **Tablero creado por el PO** en GitHub Projects:
  - Columnas Backlog, Todo, In Progress, In Review, Done.
  - Workflows activos: *Auto-add* (`is:issue is:open`), *Item closed → Done*, *Pull request merged → Done* y *Pull request linked to issue → In Review*.
  - En el tablero solo se siguen issues; los PR se quitan.

### Decisiones
- **Refinamiento progresivo:** solo se detallan las dos próximas iteraciones. Las demás son épicas que se dividen en historias justo antes de empezarlas.
- Ramas con el número de issue: `feature/18-zona-habitual`.
- Columnas del tablero en inglés: **Backlog, Todo, In Progress, In Review, Done**.

### Problemas / bloqueos
- GitHub Projects solo se gestiona con la API GraphQL, que no está disponible desde la sesión de Claude: el PO crea el tablero.
- La validación del YAML de las plantillas falló (signos `¿?` sin comillas dentro de `{ }`) y el commit se subió igualmente, sin `CONTRIBUTING.md`. Se corrigió en un segundo commit. Lección: que un fallo en un paso detenga los siguientes.

### Aprendido
- Detallar todo el backlog de golpe es desperdicio: lo lejano cambiará (ya pasó con MySQL → PostGIS).

### Retro de la Fase D
- ✅ Diseñar antes de programar ha permitido cambiar la base de datos y añadir la API sin coste.
- ✅ Los PR con revisión del PO funcionan bien (relatos de casos, nomenclatura ZBID/ZBZ).
- 🔧 Las revisiones de GitHub quedan «pendientes» hasta que se envían: enviar siempre con *Finish your review*.

### Siguiente paso
Fusionar este PR → arrancar la **iteración 0** con la issue #4 (Docker + PostGIS).

---

## [2026-10-31] #008 — Iteración 0: entorno Docker con PostGIS (#4)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~1 h 15 min

### Objetivo de la sesión
Arrancar todo el entorno de desarrollo con un solo comando.

### Hecho
- **Revisión de los libros del proyecto** y mapa de lecturas por iteración (en el proyecto de claude.ai).
- **Imagen `app`:** PHP 8.4-fpm con `pdo_pgsql`, `redis`, `intl`, `gd`, `zip`, `bcmath`, `pcntl` y `opcache`, más Composer. Usuario sin privilegios con el UID del host.
- **`docker-compose.yml`:**
  - Servicios `app`, `web` (nginx), `db` (postgis/postgis:16-3.4), `redis`, `mailpit` y `node` (perfil `front`).
  - *Healthchecks*, y la app espera a que la base de datos y Redis estén sanos.
- **Script de inicio de PostgreSQL:** extensiones `postgis` y `unaccent`, y base de datos `zbooks_testing`.
- **Esqueleto de Laravel 13** con `.env.example` configurado para PostgreSQL, Redis (caché, sesiones y colas) y Mailpit.
- **`Makefile`** con atajos (`make up`, `make shell`, `make test`, `make psql`…).

### Verificación
- `http://localhost` → 200
- `SELECT postgis_version()` → 3.4
- Mailpit en `http://localhost:8025` → 200
- Redis responde y las migraciones se ejecutan contra PostGIS.

### Decisiones (→ ADR)
- **ADR-0009:** Laravel 13 + PHP 8.4. Laravel 13 es la versión estable actual y Pest 5 exige PHP 8.4.
- Del esqueleto **no** se incluyen sus workflows de `.github` ni sus `AGENTS.md`/`CLAUDE.md` genéricos.
- PostgreSQL 16 en lugar de 17 de momento, por coincidir con la imagen documentada.

### Problemas / bloqueos
Todos son del entorno de Claude, no del proyecto; en la máquina del PO no ocurren:
- Faltaban dominios permitidos (Packagist, Docker Hub, PECL, Debian). El PO los añadió.
- Docker Hub limitaba las descargas anónimas (*429*). Se resolvió con un token de solo lectura del PO; **revocarlo al terminar**.
- El proxy del sandbox solo admite HTTPS y GitHub solo permite descargas de los repos de la sesión. Se usó una imagen base local con la CA del proxy y Composer con `--prefer-source`, sin tocar nada del repo.
- El PO paró sin querer una tarea larga. Desde entonces, las tareas largas se lanzan en segundo plano.
- `CREATE DATABASE … OWNER CURRENT_USER` no es válido en PostgreSQL: se eliminó el `OWNER`.

### Aprendido
- Montar un volumen sobre `/docker-entrypoint-initdb.d` sustituye los scripts de la imagen: hay que crear las extensiones uno mismo.
- Los scripts de inicio solo se ejecutan con el volumen de datos vacío (`docker compose down -v` para repetirlos).

### Siguiente paso
Revisión del PR → #5 (Tailwind, Livewire y layout).

---

## [2026-10-31] #009 — Iteración 0: Laravel base, Tailwind, Livewire y layout (#5)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~45 min

### Objetivo de la sesión
Tener el esqueleto web con identidad visual propia y una portada que explique zBooks.

### Hecho
- **Livewire 4** instalado; Alpine.js llega incluido con él.
- **Tailwind 4** con tokens de diseño propios en `resources/css/app.css`.
- **Fuentes alojadas en el proyecto** con `@fontsource`, sin CDN externo (mejor para el RGPD).
- **Layout base** (`resources/views/layouts/app.blade.php`):
  - Cabecera con buscador de ZBID y menú móvil.
  - Pie de página y enlace «Saltar al contenido».
- **Portada:** presentación, la etiqueta del libro como elemento protagonista y «Cómo funciona» en tres pasos.
- **Ruta `/zbid`:** de momento informa de que la búsqueda llega en la v0.4.0.
- Logotipo SVG propio y favicon.
- Capturas en `docs/capturas/v0.1.0/`.

### Decisiones
- **Identidad visual:**
  - Inspirada en los objetos reales de zBooks: la etiqueta amarilla del libro, el sello violeta de las fichas de biblioteca y la ruta verde del mapa.
  - Tipografías: Bricolage Grotesque (interfaz), Source Serif 4 (lectura) e IBM Plex Mono (solo para los códigos ZBID).
- **Botones de funciones futuras** visibles pero desactivados, indicando la versión en que llegan.
- **Zona horaria:** las fechas se guardan en **UTC** y se muestran en `Europe/Madrid` (`app.display_timezone`). Se aparta de la issue, que pedía `Europe/Madrid` como zona de la app: guardar en hora local da problemas con el cambio de hora.
- Versión de la app en `config/app.php` (`0.1.0-dev`).

### Problemas / bloqueos
- En las primeras capturas el menú móvil salía abierto con los dos iconos. Livewire 4 solo inyecta Alpine en páginas con componentes Livewire. Se añadieron `@livewireStyles`/`@livewireScripts` al layout y la regla `[x-cloak]`.

### Aprendido
- Revisar capturas antes de dar algo por terminado detecta fallos que los tests no ven.

### Siguiente paso
Revisión del PR → #6 (Pest, Pint y Larastan).

---

## [2026-10-31] #010 — Revisión de la planificación con mirada de producto
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~20 min

### Objetivo de la sesión
Repasar qué le faltaba a la planificación vista por un PM y convertir lo útil en backlog.

### Hecho
- Nota completa de la conversación en [`docs/notas/2026-10-31-revision-planificacion.md`](notas/2026-10-31-revision-planificacion.md), para retomarla más adelante.
- Issues nuevas, enlazadas en sus épicas:
  - #32: código QR en la etiqueta y la ficha (v0.3.0).
  - #33: privacidad del ciclo: autores anónimos y horas en franjas (v0.4.0).
  - #34: seeders de demostración en Barcelona (v0.5.0).
  - #35: eventos oficiales desde el panel de administración (v0.9.0).
  - #37: amigos (v0.10.0).
- ADR-0010 (Propuesto): privacidad en el ciclo del libro.

### Decisiones
- **Sembrar el mapa** con datos de demo en Barcelona, una vez existan las funciones geoespaciales.
- **Eventos como motor de captación:** moderadores y admins podrán crearlos desde Filament.
- **QR** a la vez que el ZBID, generado como SVG con `chillerlan/php-qrcode`.
- **Privacidad:**
  - La ubicación se muestra casi exacta y el libro aparece al momento.
  - La hora se guarda exacta, pero en público solo se ve la franja.
  - Quién libera y quién captura es anónimo, salvo entre amigos. El admin lo ve todo.
- **Publicar en lotes, descartado:** dejaría el libro horas invisible. El anonimato resuelve el problema de raíz.
- Aparcados: métrica principal, proveedor de mapas para producción, piezas legales, observabilidad y proceso.

### Aprendido
- En un producto de red, el problema del arranque en frío pesa más que cualquier funcionalidad.
- En privacidad conviene preguntarse qué dato permite identificar a alguien. Aquí era el autor, no la hora.

### Siguiente paso
Revisión del PR → #6 (Pest, Pint y Larastan).

---

## [2026-11-01] #011 — Iteración 0: Pest, Pint, Larastan y PHP Insights (#6)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~40 min

### Objetivo de la sesión
Tener tests, estilo y análisis estático funcionando antes de escribir funcionalidades.

### Hecho
- **Pest 5.3** sustituye a PHPUnit como forma de escribir tests (por debajo sigue usando PHPUnit 12).
- **Tests contra PostgreSQL + PostGIS** (`zbooks_testing`), no SQLite. Un test comprueba que la base es la correcta y otro calcula una distancia real en Barcelona con `ST_Distance`.
- **Tests de la portada y del buscador de ZBID:** mayúsculas, recorte a 20 caracteres y escapado del HTML.
- **Tests de arquitectura** (`tests/Arch`): sin funciones de depuración, `env()` fuera de la app, modelos que extienden de Eloquent y los *presets* `php` y `security` de Pest.
- **Pint** con el preset `laravel` y **Larastan 3.13** (PHPStan 2.3) a nivel 6.
- **PHP Insights 2.15** (a petición del PO, como en *DevOps with Laravel*): puntúa el código en calidad, complejidad, arquitectura y estilo. Primera nota: 100 en las cuatro.
- Comandos `composer test|lint|format|analyse|insights|check` y sus equivalentes en el `Makefile`.
- `CONTRIBUTING.md`: tabla de comandos y qué va en cada carpeta de tests.

### Decisiones
- **`LazilyRefreshDatabase`** en los tests de Feature: migra una vez y cada test corre dentro de una transacción que se deshace. Es rápido, y los tests que no tocan la base no pagan nada.
- **`tests/` fuera de Larastan:** PHPStan no entiende el `$this` de los closures de Pest y da falsos positivos. Los tests ya se verifican al ejecutarlos.
- **Nivel 6 de Larastan:** obliga a tipar parámetros y retornos (incluido el contenido de los arrays con PHPDoc) sin llegar a la rigidez de los niveles 8–10. Se puede subir más adelante.
- **Pint sin reglas extra:** el preset `laravel` tal cual, para no discutir de estilo.
- **PHP Insights con mínimo 90** en cada categoría, el mismo umbral que propone el libro. Se configura en `config/insights.php`, así que el local y la CI usan el mismo.
- **Entornos de base de datos:** `zbooks_testing` solo para tests automáticos; `zbooks` para desarrollo, demos y seeders.
- **Se añade preproducción (*staging*)** a la épica de producción (#28), con una copia de producción con datos anonimizados. Sustituye al «sin staging» del análisis.
- **Pint manda en el estilo:** en PHP Insights se desactivan tres reglas de llaves que chocan con Pint, y el límite de línea sube de 80 a 120 caracteres.

### Problemas / bloqueos
- En el entorno de Claude, `phpstan/phpstan` solo se distribuye como zip de GitHub, y esas descargas están bloqueadas. Se preparó el zip desde un clon git en la caché de Composer. No afecta al repositorio.
- PHP Insights necesita internet para revisar avisos de seguridad de las dependencias. En el entorno de Claude solo funciona pasándole el proxy; en tu máquina y en la CI, sin hacer nada.
- Primera versión del test de arquitectura de `env()`: incluía `routes/`, pero esos ficheros no tienen clases y Pest no los analiza. Se comprobó metiendo un `dd()` a propósito en `User` y viendo que el test fallaba.

### Aprendido
- Un test que nunca has visto fallar no demuestra nada: conviene romper el código a propósito una vez.
- Dos herramientas de estilo acaban chocando. Hay que elegir cuál manda y configurar la otra para que no compita.

### Siguiente paso
Revisión del PR → #7 (integración continua con GitHub Actions).

---

## [2026-11-01] #012 — Iteración 0: integración continua con GitHub Actions (#7)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~20 min

### Objetivo de la sesión
Que cada PR ejecute solo las mismas comprobaciones que `make check`.

### Hecho
- Workflow `.github/workflows/ci.yml`, validado con `actionlint` antes de subirlo.
- **Job «Calidad»:** Pint, Larastan y PHP Insights. Los errores salen como anotaciones en las líneas del PR (`--error-format=github`, `--format=github-action`).
- **Job «Tests»:** Pest contra PostGIS y Redis como servicios de GitHub.
- Badge de CI en el README.

### Decisiones
- **Dos jobs en paralelo:** el de calidad no necesita base de datos y avisa antes.
- **El mismo script SQL que en local** (`docker/postgres/init/`) prepara la base en la CI. Si cambia, cambia en los dos sitios a la vez.
- **Assets compilados en la CI:** las vistas usan `@vite` y sin el manifest los tests de la portada fallan. De paso se comprueba que el front compila.
- **Sin Docker en la CI por ahora:** `setup-php` es más rápido que construir nuestra imagen. Se valorará cuando haya imágenes de producción (épica #28), como propone *DevOps with Laravel*.
- **Las ejecuciones antiguas de un PR se cancelan** al subir un commit nuevo.

### Siguiente paso
Regla de `master` con los checks obligatorios (PO) → #8 (esqueleto de la API v1).

---

## [2026-11-01] #013 — Iteración 0: esqueleto de la API v1 (#8)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~40 min

### Objetivo de la sesión
Dejar fijadas las convenciones de la API antes de la primera funcionalidad (ADR-0007).

### Hecho
- **Rutas por versión:** `routes/api.php` monta `routes/api/v1.php` con el prefijo `/api/v1` y los nombres `api.v1.*`.
- **Errores en Problem Details** (RFC 9457) con `ProblemDetailsRenderer`:
  - Cubre 401, 403, 404, 405, 422, 429 y 500, siempre con `application/problem+json`.
  - Los errores de validación añaden `errors` por campo.
  - Un 429 conserva la cabecera `Retry-After`.
  - Un 500 no muestra el mensaje real fuera de local.
- **Limitador `api`:** 60 peticiones por minuto, por usuario o, si no hay sesión, por IP.
- **`GET /api/v1/health`:** comprueba la base de datos y Redis; responde 200 o 503.
- **Scramble:** documentación OpenAPI 3.1 en `/docs/api` (solo en local, de momento).
- **15 tests de contrato** (24 en total). Se comprobó que fallan con el renderer desactivado.

### Decisiones
- **`type: about:blank`** para los errores HTTP genéricos: el título es la frase estándar del código («Not Found»), como pide el RFC. Los textos en español van en `detail` y son traducibles con `__()`.
- **Tipo propio solo para la validación** (`/problems/validation-error`), porque añade el campo `errors`. Cada error de dominio futuro (p. ej. un ZBID inexistente) tendrá su propio `type`.
- **El renderer solo actúa en `/api/*`:** la web conserva sus páginas de error.
- **Sanctum todavía no se instala:** llega con la autenticación en la v0.2.0 (#13). Por eso no se usó `php artisan install:api`, que lo instala.
- **Nombres de clases en inglés** (`HealthController`, `ProblemDetailsRenderer`), como los recursos de la API. Las vistas, los tests y los comentarios siguen en español.
- **`/docs/api` solo en local:** se decidirá en la épica de producción (#28) si se publica.

### Problemas / bloqueos
- Scramble documentaba la respuesta de salud como `string` porque el código de estado es una variable. Se resolvió con un `@response` en PHPDoc.
- PHP Insights tomó «si todo funciona» por un `TODO`. Se reescribió la frase.
- Pint y PHP Insights volvieron a chocar, esta vez por las líneas en blanco del PHPDoc. Manda Pint.

### Siguiente paso
Revisión del PR → #9 (README: cómo arrancar el proyecto) y cierre de la iteración 0.

---

## [2026-11-01] #014 — Iteración 0: idiomas y API en inglés (#42)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~40 min

### Objetivo de la sesión
Decidir los idiomas antes de cerrar la v0.1.0 y dejar el contrato de la API en inglés.

### Hecho
- **ADR-0011:** código y API en inglés; web en español, inglés o catalán según cada usuario.
- Issues **#42** (API en inglés, v0.1.0) y **#43** (web multiidioma, v0.2.0).
- `APP_LOCALE=en` y textos de Problem Details en inglés.
- Descripción de `/docs/api` y PHPDoc del endpoint de salud en inglés.
- Test nuevo: la API responde en inglés aunque exista la traducción española y el cliente la pida. Se comprobó que falla con `APP_LOCALE=es`.

### Decisiones
- **Primera versión descartada:** prefijo en la URL (`/es/...`) y un middleware que forzaba el inglés en la API. El PO señaló que daba varias URL al mismo recurso y que el middleware sobraba si el idioma base es el inglés.
- **Una URL por recurso** y el idioma como preferencia: perfil → cookie → navegador → español. Lo que se busca en Google es el contenido de los libros, que no se traduce, así que el prefijo aportaba poco.
- **Español como idioma de reserva:** el público inicial está en España y el rastreador de Google indexará la web en ese idioma.
- **Sin `mcamara/laravel-localization`:** está pensado para prefijos; un middleware propio basta.
- **Catalán** por las demos en Barcelona.

### Siguiente paso
Revisión del PR → #9 (README: cómo arrancar el proyecto) y cierre de la iteración 0.

---

## [2026-11-01] #015 — Iteración 0: README para arrancar el proyecto (#9)
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~30 min

### Objetivo de la sesión
Que cualquiera pueda clonar y arrancar zBooks siguiendo solo el README.

### Hecho
- Sección «Cómo arrancarlo» en el README: requisitos, instalación, URLs, comandos habituales, tests y problemas frecuentes.
- **`make setup`:** crea el `.env`, construye la imagen, instala dependencias, genera la clave, migra y compila el front. Se puede repetir: no regenera la clave.
- **`make assets`:** compila el front sin Vite en marcha.
- Stack del README actualizado (Laravel 13, PHP 8.4, Livewire 4, Tailwind 4).

### Comprobación
- Clon limpio en otra carpeta y con otro nombre de proyecto de Compose (bases vacías): `make setup` → portada, `/api/v1/health`, `/docs/api` y buscador de ZBID en 200; 25 tests en verde.
- Repetir `make setup` deja la misma `APP_KEY`.
- `make setup UID=… GID=…` llega a los argumentos de la imagen.

### Decisiones
- **Un solo comando de instalación** en lugar de una lista de pasos: menos sitios donde equivocarse.
- **Sin PHP, Composer ni Node en el equipo:** todo corre en contenedores.

### Siguiente paso
Revisión del PR → cierre de la iteración 0: etiqueta v0.1.0, release, CHANGELOG y retrospectiva.

---

## [2026-11-01] #016 — Cierre de la iteración 0 y v0.1.0
**Iteración:** 0 (v0.1.0)  ·  **Duración:** ~1 h

### Objetivo de la sesión
Cerrar la v0.1.0 y revisar el estado del proyecto antes de la iteración 1.

### Hecho
- **#46:** la CI pasa a `ubuntu-24.04` fijo y a acciones con Node 24, y Dependabot vigila sus versiones.
- **[Análisis del cierre](notas/2026-11-01-cierre-iteracion-0.md)** de issues, diario y estado.
- **[Análisis funcional v1.5](analisis/zBooks_Analisis_Funcional_v1.5.pdf)**, con la página «Cambios en la versión 1.5». La fuente pasa a Markdown (`analisis-funcional.md`) y `make analisis` genera el PDF.
- Backlog al día: títulos de #5 y #6, cambio de alcance en la épica #27 y issues nuevas #48 (RGPD mínimo, v0.2.0) y #49 (logs y errores a Slack, v1.0.0).
- Diario #002 cerrado.
- Versión `0.1.0` en `config/app.php`, CHANGELOG con su sección, etiqueta `v0.1.0` y release en GitHub.

### Decisiones
- **Histórico de versiones del análisis:** versión nueva al cerrar una iteración con cambios relevantes, con los cambios en su primera página. Las versiones anteriores se conservan, y entre versiones los cambios se apuntan en `cambios-pendientes.md`. La fuente de la v1.4 (ReportLab) no estaba en el repo, así que la v1.5 se rehízo en Markdown.
- **RGPD:** lo habitual del sector, adelantado a la v0.2.0, antes del registro.
- **Observabilidad por escalones:** primero logs diarios y Slack; después seguimiento de errores; por último métricas.
- **Mapas en desarrollo:** OpenStreetMap y Nominatim, como ya decía la #18. El proveedor de producción, antes de la #28.

### Retro de la iteración 0
- ✅ Ninguna issue planificada cambió de alcance: la fase D compensó.
- ✅ Los checks son los mismos en local y en la CI, y `master` los exige.
- ✅ La revisión del PO cambió decisiones de verdad (privacidad, idiomas, preproducción).
- ✅ Validar los tests rompiendo el código a propósito.
- 🔧 Las entradas del diario y los títulos de las issues se quedaron desfasados sin que nadie lo viera. Revisarlos en cada cierre.
- 🔧 La primera versión de la ADR-0011 se escribió antes de entender del todo lo que quería el PO. En decisiones de producto, preguntar antes de escribir.
- 🔧 La rama de la #8 se creó antes de fusionar la CI (#40) y hubo que rebasarla. Empezar cada rama desde el `master` ya actualizado.

### Siguiente paso
Sesión de refinamiento de la v0.2.0 (alcance, orden, paquetes y métrica principal) → iteración 1.

---

## [2026-11-01] #017 — Presentación del avance y guía del desarrollador (#51)
**Iteración:** 1 (v0.2.0), preparación  ·  **Duración:** ~45 min

### Objetivo de la sesión
Presentar el avance de la v0.1.0 y preparar al PO para desarrollar y revisar PRs.

### Hecho
- **Presentación para dirección** (artefacto de Slides): idea, método, hoja de ruta, entregas, cambios por iteración, privacidad, riesgos y dos decisiones pedidas (métrica principal y prioridades de la v0.2.0). Discurso en las notas.
- **Release v0.1.0:** la crea el PO desde GitHub con las notas del CHANGELOG, porque la sesión de Claude no tiene permiso para crear etiquetas ni releases.
- **[Guía del desarrollador](GUIA-DESARROLLADOR.md):** comandos, flujo de issue a PR, archivos de referencia y casos frecuentes.

### Decisiones
- **Notas de la release desde el CHANGELOG**, no las autogeneradas por GitHub: explican qué trae la versión, no qué PRs se fusionaron.
- **Guía aparte de `CONTRIBUTING.md`:** CONTRIBUTING fija las normas; la guía es el manual práctico y crece con cada caso que se repita.
- **Cambiar de tarea:** se recomienda un commit temporal en la rama en lugar de `stash`, porque queda a salvo en GitHub.

### Siguiente paso
Revisión del PR → sesión de refinamiento de la v0.2.0.

---

## [2026-11-01] #018 — Refinamiento de la v0.2.0
**Iteración:** 1 (v0.2.0)  ·  **Duración:** ~1 h

### Objetivo de la sesión
Ajustar el alcance, el orden y los detalles de la v0.2.0 antes de programar.

### Hecho
- **Alcance:** #14 (dispositivos), #17 (editar perfil) y #19 (zona temporal) pasan a la v0.3.0.
- **#43 dividida:** la base (textos fuente en inglés y español por defecto) se queda; inglés, catalán y selector pasan a la #53 (v0.3.0).
- **#18 replanteada:** la zona se fija por geolocalización o con un punto en el mapa, redondeado a ~500 m. La zona por barrio o ciudad pasa a la #54 (v0.3.0).
- **Criterios concretados:** username y contraseña (#10), reenvío del email de verificación por API (#12), objetivo de #16 corregido (contradecía el ADR-0010).
- **ADR-0012** (paquetes) y **ADR-0013** (zonas); diagrama ER actualizado.
- **#15 asignada al PO**: su primera tarea de desarrollo.
- Versión `0.2.0-dev`. Cambios del análisis anotados en `cambios-pendientes.md`.

### Decisiones
- **Orden:** #48 (RGPD) → #15 (roles) → #10 (registro web) → #11 (login) → #12 y #13 (API) → #16 (perfil público) → #18 (zona) → #43 (base de idiomas).
- **Fortify en lugar de Breeze:** motor de autenticación sin pantallas; las vistas llevan el diseño de zBooks y la API usa las mismas acciones.
- **Username:** en minúsculas, único sin distinguir mayúsculas, sin nombres reservados y no editable de momento (va en la URL del perfil).
- **Contraseña:** regla estándar de Laravel; se endurecerá si hace falta.
- **Duración de los tokens:** se afinará cuando se defina la app móvil.
- **Verificación del email:** un único flujo para la web y la API; la app consulta `/api/v1/me` para saber si ya está verificado.
- **Zonas:** dos modos (radio o área), con la precisión que elija cada persona. Barcelona desde Open Data BCN y el resto desde OpenStreetMap, bajo demanda.
- **Métrica principal:** libros capturados al mes.
- **Diseño:** pantallas de registro, login y perfil antes de programarlas.

### Siguiente paso
Diseño de las pantallas de registro, login y perfil → #48 (Claude) y #15 (PO) en paralelo.

