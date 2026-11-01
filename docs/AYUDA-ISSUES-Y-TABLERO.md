# Ayuda: issues, tablero, etiquetas y flujos

Documento de consulta para entender cómo encajan las piezas de GitHub que usamos. Las normas están en [`CONTRIBUTING.md`](../CONTRIBUTING.md); el día a día, en la [guía del desarrollador](GUIA-DESARROLLADOR.md).

## 1. La regla para no liarse

> **Un número `#N` es siempre una issue o un PR. Una tarjeta no tiene número propio: es la misma issue vista en el tablero.**

- Issues y PRs comparten la misma numeración: si la última issue es la #56, el siguiente PR será el #57. Por eso «#41» puede ser un PR y «#43» una issue.
- Cuando digo «empiezo con la #48», hablo de la **issue** #48. Su tarjeta en el tablero es solo la forma de ver en qué estado está.
- Si una issue no aparece en el tablero, existe igual: falta añadir su tarjeta (sección 5).

## 2. Las piezas

| Pieza | Qué es | Dónde se ve | Ejemplo |
|---|---|---|---|
| **Issue** | Una unidad de trabajo: historia, tarea técnica, bug o épica | Pestaña *Issues* del repo | #10 Registro en la web |
| **Tarjeta** | La issue dentro del tablero, con su **Status** | GitHub Projects | La #10 en la columna *Todo* |
| **Pull request (PR)** | Una propuesta de cambio de código, desde una rama | Pestaña *Pull requests* | #55 Refinamiento de la v0.2.0 |
| **Hito** (*milestone*) | La versión a la que pertenece una issue: el **cuándo** | Campo de la issue | `v0.2.0 · Usuarios y auth` |
| **Etiqueta** (*label*) | Clasifica la issue: tipo, prioridad y área | Campo de la issue | `tipo: historia`, `prio: must` |
| **Épica** | Una issue grande que agrupa trabajo futuro; se divide en issues al refinarla | Issue con `tipo: épica` | #21 Liberar, capturar y diario |
| **Rama** | La copia del código donde se trabaja una issue | Git | `feature/10-registro-web` |

**Hito y Status responden a preguntas distintas:**
- el **hito** dice *para qué versión* es (v0.2.0, v0.3.0…);
- el **Status** de la tarjeta dice *en qué punto está* (Backlog, Todo, In Progress, In Review, Done).

## 3. Etiquetas

Cada issue lleva **un tipo**, **una prioridad** y **una o más áreas**.

**Tipo** (lo pone la plantilla al crear la issue)

| Etiqueta | Cuándo |
|---|---|
| `tipo: historia` | Algo que nota el usuario. Formato «Como… quiero… para…» y criterios de aceptación |
| `tipo: técnica` | Trabajo necesario que el usuario no ve: infraestructura, configuración, refactor |
| `tipo: docs` | Solo documentación |
| `tipo: épica` | Agrupa varias historias de una iteración futura |
| `bug` | Algo que no funciona como debería |

**Prioridad** (MoSCoW)

| Etiqueta | Significado |
|---|---|
| `prio: must` | Imprescindible: sin esto la versión no sale |
| `prio: should` | Importante, pero la versión podría salir sin ello |
| `prio: could` | Deseable, si hay tiempo |

**Área:** la parte del producto que toca: `auth`, `usuarios`, `libros`, `ciclo`, `geo`, `api`, `admin`, `comunidad`, `gamificación`, `social`, `infra`.

**Estado especial:** `necesita refinamiento` indica que faltan los criterios de aceptación. No se empieza hasta quitarla. Las épicas la llevan siempre.

Las etiquetas en inglés (`enhancement`, `question`…) son las que trae GitHub por defecto; no las usamos.

## 4. Convenciones

| Qué | Formato | Ejemplo |
|---|---|---|
| Título de issue | En español, describe el resultado | «Registro en la web con verificación de email» |
| Rama | `tipo/<nº issue>-<descripción>` | `feature/10-registro-web`, `fix/42-zbid-duplicado` |
| Commit | `tipo(ámbito): descripción en minúscula` | `feat(auth): verificación de email` |
| Título de PR | Como un commit, con la issue al final | `feat(auth): registro en la web (#10)` |
| Cuerpo de PR | Plantilla, con `Closes #10` | Cierra la issue al fusionar |
| Referencia sin cerrar | `Refs #10` o solo `#10` | Enlaza la issue pero no la cierra |

Palabras que cierran la issue al fusionar el PR: `Closes`, `Fixes` o `Resolves`, seguidas del número.

## 5. El tablero: qué es automático y qué haces tú

### Configuración de los flujos (una vez)

Proyecto → menú `⋯` → **Workflows**:

| Flujo | Configuración | Efecto |
|---|---|---|
| **Auto-add to project** | Repo `zStrikke/zBooks`, filtro `is:issue is:open` | Cada issue nueva o modificada entra en el tablero. El plan gratuito permite **solo uno**: edita el que ya existe |
| **Item added to project** | Status → `Backlog` | La tarjeta nueva aparece en Backlog |
| **Item closed** | Status → `Done` | Al cerrarse la issue, la tarjeta pasa a Done |
| **Pull request merged** | Status → `Done` | Lo mismo para PRs, si alguno entra en el tablero |
| **Item reopened** | Status → `Todo` | Si se reabre, vuelve a Todo |

El añadido automático **no es retroactivo**. Las issues que ya existían se añaden una vez a mano: abajo del tablero, **+ Add item** → escribe `#` y el número, o *Add items from repository* para añadir varias.

**Vista recomendada:** una pestaña del tablero filtrada por `milestone:"v0.2.0 · Usuarios y auth"`, para ver solo la iteración actual. Al cambiar de iteración, se cambia el filtro.

### Quién mueve cada tarjeta

| Paso | Columna | ¿Quién? |
|---|---|---|
| Se crea la issue | → **Backlog** | 🤖 Automático |
| Refinamiento: entra en la iteración actual | Backlog → **Todo** | ✋ Tú (y le pones el hito si no lo tiene) |
| Alguien empieza a trabajarla | Todo → **In Progress** | ✋ Quien la coge (y se la asigna) |
| Se abre el PR con `Closes #N` | In Progress → **In Review** | ✋ Quien abre el PR |
| Se fusiona el PR | → **Done** (la issue se cierra) | 🤖 Automático |
| Se aplaza a otra versión | → **Backlog** (y cambia el hito) | ✋ Tú |

Yo no tengo acceso al tablero desde mis sesiones: puedo crear y editar issues, pero los movimientos de tarjetas los haces tú.

## 6. Flujos de ejemplo

### A. Una historia normal (#10 Registro en la web)

1. **Creación:** la issue #10 se crea con la plantilla «Historia», hito `v0.2.0`, etiquetas `tipo: historia`, `prio: must`, `área: auth`. 🤖 Su tarjeta entra en **Backlog**.
2. **Refinamiento:** se revisan los criterios. ✋ La tarjeta pasa a **Todo**, en su orden.
3. **Empezar:** ✋ te asignas la #10 y la mueves a **In Progress**. Creas la rama `feature/10-registro-web` desde `master`.
4. **PR:** abres «feat(auth): registro en la web (#10)» con `Closes #10` en el cuerpo. ✋ Mueves la tarjeta a **In Review**. Es el PR #60, por ejemplo: tiene su propio número.
5. **Revisión:** comentarios, cambios en la misma rama, CI en verde, aprobación.
6. **Fusión:** 🤖 la #10 se cierra sola y su tarjeta pasa a **Done**.

### B. Un bug que aparece mientras trabajas

1. Encuentras un fallo que no es de tu issue: **no lo arregles en tu rama**.
2. Creas una issue con la plantilla «Bug» (#61, por ejemplo). 🤖 Entra en **Backlog**.
3. Si es urgente, ✋ ponle el hito actual y muévela a **Todo**; si no, se decide en el próximo refinamiento.
4. Cuando toque, sigue el flujo A con una rama `fix/61-…`.

### C. Una issue que se aplaza

1. En el refinamiento se decide que la #17 no entra en la v0.2.0.
2. Se cambia su hito a `v0.3.0` y se comenta el motivo (esto puedo hacerlo yo).
3. ✋ Mueves su tarjeta a **Backlog**.

### D. Una issue que se divide

1. La #43 resulta demasiado grande.
2. Se recorta su descripción a la primera parte y se crea una issue nueva (#53) con el resto, enlazadas entre sí con un comentario.
3. 🤖 La #53 entra en **Backlog**; la #43 sigue donde estaba.

### E. Una épica que se refina

1. La épica #20 (Libros, ZBID y etiquetas) está en **Backlog** con `necesita refinamiento`.
2. En el refinamiento de la v0.3.0 se divide en historias (#70, #71…), cada una con sus criterios y el hito `v0.3.0`.
3. En la épica se marca cada historia con su número; la épica se cierra cuando se cierran todas.

### F. Un PR sin issue

Algunos PR no tienen issue propia (un refinamiento, el cierre de una versión). Su cuerpo lo dice («Sin issue propia: …») y no mueven ninguna tarjeta.

## 7. Preguntas rápidas

- **¿Dónde busco qué hacer?** En **Todo**, en el orden acordado. Lo de Backlog no se empieza sin refinar.
- **¿Puedo tener dos issues en In Progress?** Mejor no: una cada vez. Si cambias, la anterior vuelve a Todo o se queda en In Progress con un commit `wip` en su rama (ver la guía).
- **¿Quién cierra la issue?** El PR al fusionarse, gracias a `Closes #N`. Solo se cierra a mano si se descarta (con un comentario del motivo).
- **¿Y si el número que veo es de un PR?** Ábrelo: en la cabecera pone «Pull request» o «Issue».
