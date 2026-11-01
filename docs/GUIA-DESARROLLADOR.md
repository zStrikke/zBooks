# Guía del desarrollador

Lo práctico para trabajar en zBooks cada día. Las normas completas están en [`CONTRIBUTING.md`](../CONTRIBUTING.md); para instalar el proyecto, en el [README](../README.md#cómo-arrancarlo).

**Índice:** [Comandos](#1-comandos) · [Flujo de trabajo](#2-flujo-de-trabajo) · [Qué archivo consultar](#3-qué-archivo-consultar) · [Casos frecuentes](#4-casos-frecuentes)

## 1. Comandos

Se ejecutan desde la raíz del proyecto y corren dentro de Docker: no hace falta PHP en tu equipo. `make help` los lista todos.

### Día a día

| Comando | Para qué |
|---|---|
| `make up` / `make down` | Arrancar o parar el entorno |
| `make front` | Vite en modo desarrollo: los cambios de CSS y JS se ven al momento |
| `make shell` | Terminal dentro del contenedor de la app |
| `make artisan c="route:list"` | Cualquier comando de Artisan |
| `make composer c="require foo/bar"` | Cualquier comando de Composer |
| `make migrate` / `make fresh` | Migraciones / recrear la base con datos de prueba |
| `make logs s=app` | Logs de un servicio |
| `make psql` | Consola de PostgreSQL |

### Calidad: antes de cada push

| Comando | Qué hace |
|---|---|
| `make format` | **Corrige** el estilo con Pint. Primero este |
| `make lint` | Comprueba el estilo sin tocar nada |
| `make analyse` | Análisis estático con Larastan: tipos, métodos inexistentes, errores de Eloquent |
| `make insights` | Puntúa el código; falla por debajo de 90 en cualquier categoría |
| `make test` | Todos los tests, contra PostGIS (base `zbooks_testing`) |
| `make test c="--filter=health"` | Solo los tests cuyo nombre contiene «health» |
| **`make check`** | **Todo lo anterior, en el orden de la CI.** Si pasa aquí, pasa en GitHub |

La rutina: **`make format && make check`** antes de cada `git push`.

> **Para no olvidarlo:** un *hook* de Git que ejecuta `make check` antes de cada push y lo cancela si falla. Es local, no se sube al repo:
> ```bash
> printf '#!/bin/sh\nmake check\n' > .git/hooks/pre-push && chmod +x .git/hooks/pre-push
> ```
> Para saltártelo una vez (por ejemplo, para subir un trabajo a medias): `git push --no-verify`.

## 2. Flujo de trabajo

```
1. ISSUE
   Tablero: la issue pasa de Todo a In Progress y te la asignas.
   ¿No existe? Créala con su plantilla (historia, técnica o bug).
        │
2. RAMA, desde master actualizado
   git switch master && git pull
   git switch -c feature/10-registro-web        (feature | fix | docs | chore)
        │
3. CÓDIGO + TESTS
   Commits pequeños y convencionales:
   git commit -m "feat(auth): verificación de email en el registro"
        │
4. ANTES DEL PUSH
   make format && make check
   CHANGELOG (Unreleased), diario, ADR o diagramas si aplica
        │
5. PUSH + PR
   git push -u origin feature/10-registro-web
   PR con la plantilla: qué cambia, «Closes #10», cómo probarlo, checklist
   Tablero: In Review
        │
6. REVISIÓN
   CI en verde (calidad + tests).
   Comentarios en las líneas → cambios en la misma rama → push.
   Al revisar, enviar siempre con «Finish your review».
        │
7. FUSIÓN
   Con aprobación y CI en verde. «Closes #10» cierra la issue (Done).
   git switch master && git pull && git branch -d feature/10-registro-web
```

**Nombres:**
- Rama: `tipo/<nº issue>-<descripción-corta>`, por ejemplo `fix/42-zbid-duplicado`.
- Commit: `tipo(ámbito): descripción en minúscula`. Tipos: `feat`, `fix`, `docs`, `test`, `refactor`, `chore`.
- PR: el título con el mismo formato que un commit, y el número de la issue al final: `feat(auth): registro en la web (#10)`.

## 3. Qué archivo consultar

| Momento | Archivo |
|---|---|
| Dudas sobre el proceso | [`CONTRIBUTING.md`](../CONTRIBUTING.md) |
| Instalar o arrancar el proyecto | [`README.md`](../README.md) |
| Ver todos los atajos | `Makefile` (`make help`) |
| Crear una issue | `.github/ISSUE_TEMPLATE/` (se elige al crearla en GitHub) |
| Abrir un PR | `.github/pull_request_template.md`: la checklist de terminado |
| Saber qué comprueba la CI | `.github/workflows/ci.yml` |
| Apuntar tu cambio | [`CHANGELOG.md`](../CHANGELOG.md), sección `Unreleased` |
| Contar la sesión | [`docs/DIARIO.md`](DIARIO.md), con la plantilla del principio |
| Tomar una decisión importante | [`docs/adr/`](adr/): índice y `0000-plantilla.md` |
| Cambia el alcance, un requisito o el plan | [`docs/analisis/cambios-pendientes.md`](analisis/cambios-pendientes.md) |
| Cambia el diseño (tablas, clases, flujos) | [`docs/diagramas/`](diagramas/), en el mismo PR |
| Una herramienta se queja | `pint.json`, `phpstan.neon`, `config/insights.php` |

## 4. Casos frecuentes

### Empezar y organizarse

**¿Dónde busco la tarea que toca hacer ahora?**
En el **tablero de GitHub Projects**, columna **Todo**: son las issues del hito actual, listas para empezar. Coge la que acordemos (o la primera, si el orden ya está decidido), asígnatela y muévela a **In Progress**. Lo que está en **Backlog** es de iteraciones futuras: no se empieza sin refinarlo antes. Desde la terminal también puedes verlas: `gh issue list --milestone "v0.2.0 · Usuarios y auth"`.

**¿Puedo evitar crear la rama a mano?**
Sí, de tres formas. Todas enlazan la rama con la issue:
- **VS Code:** la extensión oficial **GitHub Pull Requests** tiene una vista de issues. Clic derecho en una issue → *Start Working on Issue* crea la rama y te cambia a ella. Para que use nuestro formato, en los ajustes de VS Code:
  ```json
  "githubIssues.issueBranchTitle": "feature/${issueNumber}-${sanitizedIssueTitle}"
  ```
  Cambia `feature` por `fix`, `docs` o `chore` cuando toque, o renombra la rama después (ver más abajo). La misma extensión crea y revisa PRs sin salir del editor.
- **GitHub CLI:** `gh issue develop 10 --name feature/10-registro-web --checkout`.
- **Web:** en la issue, panel derecho → *Development* → *Create a branch*.

**La tarea crece o descubro otro problema mientras trabajo.**
No lo metas en el mismo PR: crea una issue nueva (con su plantilla) y sigue con lo tuyo. Un PR pequeño se revisa en minutos; uno grande, en días.

**Tengo que tomar una decisión técnica (paquete, patrón, estructura).**
Si afecta a más de una issue o es difícil de deshacer, escribe un ADR con `docs/adr/0000-plantilla.md` y enlázalo en el PR. Si dudas, coméntalo en la issue antes de programar.

### Cambiar de tarea

**Ha surgido algo prioritario. ¿Hago stash o subo mis cambios a la rama?**
Depende de cuánto tiempo vayas a estar fuera:
- **Un rato (minutos u horas):** `git stash push -m "registro a medias"`, cambias de rama, y al volver `git stash pop`. Ojo: el stash solo existe en tu equipo y es fácil olvidarlo (`git stash list` lo muestra).
- **Más tiempo, o si quieres tenerlo a salvo:** un commit temporal en tu rama y push.
  ```bash
  git add -A && git commit -m "wip: registro a medias" --no-verify
  git push --no-verify
  ```
  Al volver, sigue trabajando encima. Antes de abrir el PR puedes deshacer ese commit sin perder los cambios con `git reset --soft HEAD~1` y volver a hacer commits limpios (si ya lo habías subido, el siguiente push necesita `--force-with-lease`).

Recomendación: **el commit temporal**. Queda en GitHub y no se pierde aunque se rompa tu equipo.

**`master` ha avanzado mientras trabajaba en mi rama.**
Trae los cambios y pon tu rama encima:
```bash
git fetch origin
git rebase origin/master
# si hay conflictos: resuélvelos, git add <fichero>, git rebase --continue
make check
git push --force-with-lease
```
`--force-with-lease` reescribe tu rama en GitHub, pero se niega si alguien más ha subido algo que tú no tienes. Nunca uses `--force` a secas, y nunca sobre `master`.

### Arreglar errores

**He hecho cambios y el proyecto ya no funciona.**
Ve de lo más barato a lo más drástico:
1. **¿Qué he tocado?** `git status` y `git diff`.
2. **Probar sin mis cambios:** `git stash`, comprueba si funciona y `git stash pop` para recuperarlos. Si sin ellos funciona, el problema está en tu código.
3. **Descartar los cambios de un fichero:** `git restore <fichero>`. De todos: `git restore .` (irreversible para lo que no está en un commit).
4. **Cachés de Laravel:** `make artisan c="optimize:clear"`.
5. **Dependencias desactualizadas** (tras un `git pull`): `make composer c=install` y `make assets`.
6. **Base de datos:** `make migrate`; si sigue mal, `make fresh` (borra los datos de desarrollo).
7. **Imagen de Docker** (tras cambios en `docker/`): `make build && make restart`.
8. **Desde cero:** `docker compose down -v` (borra las bases de datos) y `make setup`.

**Red de seguridad:** `git reflog` muestra todo lo que ha apuntado tu rama, incluso commits «perdidos» tras un reset. `git reset --hard <hash>` vuelve a cualquiera de esos puntos.

**He hecho commits en `master` (o en la rama equivocada).**
`master` está protegida, así que el push fallará: no hay daño en GitHub. Para moverlos a una rama de trabajo, **si aún no los has subido**:
```bash
# Estás en master con los commits de más
git switch -c feature/10-registro-web     # la rama nueva se lleva los commits
git switch master
git reset --hard origin/master            # master vuelve a estar como en GitHub
git switch feature/10-registro-web
```
Si los hiciste en **otra rama de trabajo**, cópialos a la buena y quítalos de la equivocada:
```bash
git log --oneline                          # apunta los hashes
git switch rama-buena && git cherry-pick <hash1> <hash2>
git switch rama-equivocada && git reset --hard HEAD~2   # 2 = número de commits a quitar
```

**Quiero cambiar el último commit.**
- Solo el mensaje o añadir algo que olvidé: `git add <fichero>` y `git commit --amend`.
- Deshacerlo pero conservar los cambios: `git reset --soft HEAD~1`.

Si ya lo habías subido, el siguiente push necesita `--force-with-lease`.

**Me equivoqué en el nombre de la rama.**
`git branch -m nombre-nuevo`. Si ya estaba en GitHub: `git push origin -u nombre-nuevo` y `git push origin --delete nombre-viejo` (si tiene un PR abierto, mejor dejar el nombre como está).

### Calidad y CI

**Pint, Larastan o PHP Insights se quejan.**
- **Pint:** `make format` lo arregla solo.
- **Larastan:** casi siempre falta un tipo (`: string`, `@return array<int, Book>`) o se llama a algo que no existe. El mensaje dice el fichero y la línea.
- **PHP Insights:** `make insights` dice qué regla falla. Si choca con Pint, manda Pint (las reglas que chocan ya están quitadas en `config/insights.php`).

No añadas excepciones ni bajes niveles para que pase: coméntalo en el PR.

**En local pasa y en la CI falla.**
- Mira el paso que falla en la pestaña *Checks* del PR: los errores salen también anotados en las líneas del código.
- Causas típicas: un fichero sin añadir al commit (`git status`), una migración o un `.env` que solo existe en tu equipo, o un test que depende del orden o de la hora.
- `make check` debe pasar con un `make fresh` reciente.

**Me piden cambios en la revisión.**
Haz los cambios en la misma rama, commit y push: el PR se actualiza solo. Contesta en cada hilo («Hecho en abc123» o por qué no) y marca la conversación como resuelta.

### Base de datos y dependencias

**Necesito cambiar una tabla.**
Crea una migración nueva: `make artisan c="make:migration add_locale_to_users_table"`. **Nunca edites una migración que ya está en `master`**: otros ya la han ejecutado. Actualiza el diagrama ER (`docs/diagramas/06-er.md`) en el mismo PR.

**Necesito un paquete nuevo.**
`make composer c="require vendor/paquete"` (o `require --dev` si es solo para desarrollo). Sube `composer.json` y `composer.lock` juntos. Si es una pieza importante (autenticación, permisos, mapas), va con un ADR.

### Documentación

**¿Dónde apunto lo que he hecho?**
- **CHANGELOG:** una línea en `Unreleased` por cada cambio visible.
- **Diario:** una entrada por sesión de trabajo, con la plantilla del principio de `docs/DIARIO.md`.
- **Análisis funcional:** si cambia el alcance, un requisito o el plan, una línea en `docs/analisis/cambios-pendientes.md`.
