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

## [2026-10-30] #002 — Debate de funcionalidades (abierto)
**Iteración:** Pre-0 (análisis)  ·  **Duración:** en curso

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
- [ ] Confirmar cuenta de GitHub para crear repo y tablero.

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
