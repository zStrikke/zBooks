#!/usr/bin/env bash
# Qué ha llegado con el último git pull y qué comando hace falta para que el
# entorno siga funcionando. Solo informa: no ejecuta nada.
# Uso: make tras-pull              (desde el último pull o merge: ORIG_HEAD)
#      make tras-pull desde=v0.1.0 (desde cualquier commit, rama o etiqueta)

set -u

if [ -t 1 ]; then
    OK=$'\033[32m'; AVISO=$'\033[33m'; DIM=$'\033[2m'; BOLD=$'\033[1m'; OFF=$'\033[0m'
else
    OK=''; AVISO=''; DIM=''; BOLD=''; OFF=''
fi

DESDE="${1:-ORIG_HEAD}"

if ! git rev-parse --verify --quiet "$DESDE" >/dev/null; then
    echo "No encuentro «$DESDE». Haz antes un git pull, o indica desde dónde: make tras-pull desde=<commit>"
    exit 1
fi

FICHEROS=$(git diff --name-only "$DESDE" HEAD)

if [ -z "$FICHEROS" ]; then
    echo "${OK}✓${OFF} No ha cambiado nada desde $(git rev-parse --short "$DESDE"): no hace falta hacer nada."
    exit 0
fi

# qué ha cambiado ; ficheros (expresión regular) ; comando
REGLAS=(
    "Imagen de Docker;^(docker/|docker-compose\.yml);make build && make restart"
    "Dependencias de PHP;^composer\.(json|lock)$;make composer c=install"
    "Dependencias del front;^package(-lock)?\.json$;make assets (y reinicia make front si lo tienes en marcha)"
    "CSS y JS;^(resources/(css|js)/|vite\.config\.js$);make assets, o make front mientras desarrollas"
    "Migraciones;^database/migrations/;make migrate"
    "Seeders y factories;^database/(seeders|factories)/;make fresh si quieres los datos de prueba nuevos (borra los de desarrollo)"
    "Variables de entorno;^\.env\.example$;compara tu .env con .env.example y añade las variables nuevas"
    "Código de la app;^app/;make queue-restart: el worker de la cola sigue con el código viejo"
)

total=$(printf '%s\n' "$FICHEROS" | wc -l | tr -d ' ')
echo "${BOLD}Desde $(git rev-parse --short "$DESDE") han cambiado $total ficheros${OFF}"

pendientes=0
for regla in "${REGLAS[@]}"; do
    IFS=';' read -r nombre patron comando <<< "$regla"
    coinciden=$(printf '%s\n' "$FICHEROS" | grep -E "$patron" || true)
    [ -z "$coinciden" ] && continue

    pendientes=$((pendientes + 1))
    n=$(printf '%s\n' "$coinciden" | wc -l | tr -d ' ')
    echo
    echo "${AVISO}●${OFF} ${BOLD}$nombre${OFF}"
    printf '%s\n' "$coinciden" | head -5 | sed "s/^/    ${DIM}/; s/\$/${OFF}/"
    [ "$n" -gt 5 ] && echo "    ${DIM}… y $((n - 5)) más${OFF}"
    echo "    → $comando"
done

echo
if [ "$pendientes" -eq 0 ]; then
    echo "${OK}✓${OFF} Nada que reconstruir: los cambios se ven al recargar la página."
else
    echo "Ejecuta los comandos en este orden. Si algo sigue fallando: docs/GUIA-DESARROLLADOR.md, «Arreglar errores»."
fi
