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
- [x] **Eventos:** con límite de plazas y lista de espera; se pueden vincular a una zona OBCZ.
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
