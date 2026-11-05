#!/usr/bin/env bash
# Pone el entorno al día después de un git pull: mira qué ha llegado, ejecuta
# los comandos que hacen falta y resume qué hizo. Lo que borra datos o toca
# ficheros personales (make fresh, el .env) solo se avisa, nunca se ejecuta.
# Uso: make post-pull                (desde el último pull o merge: ORIG_HEAD)
#      make post-pull desde=v0.1.0   (desde cualquier commit, rama o etiqueta)
#      make post-pull ver=1          (solo lista, no ejecuta nada)

set -u

if [ -t 1 ]; then
    OK=$'\033[32m'; KO=$'\033[31m'; AVISO=$'\033[33m'; DIM=$'\033[2m'; BOLD=$'\033[1m'; OFF=$'\033[0m'
else
    OK=''; KO=''; AVISO=''; DIM=''; BOLD=''; OFF=''
fi

DESDE="${DESDE:-ORIG_HEAD}"
SOLO_VER="${VER:-}"

if ! git rev-parse --verify --quiet "$DESDE" >/dev/null; then
    echo "No encuentro «$DESDE». Haz antes un git pull, o indica desde dónde: make post-pull desde=<commit>"
    exit 1
fi

FICHEROS=$(git diff --name-only "$DESDE" HEAD)
desde_corto=$(git rev-parse --short "$DESDE")

if [ -z "$FICHEROS" ] && [ -L public/storage ]; then
    echo "${OK}✓${OFF} No ha cambiado nada desde $desde_corto: no hace falta hacer nada."
    exit 0
fi

# qué ha cambiado ; ficheros (expresión regular) ; comando ; auto (se ejecuta) | aviso (solo se dice)
# El orden importa: primero la imagen, luego las dependencias, el front, la base de datos y la cola.
REGLAS=(
    "Imagen de Docker;^(docker/|docker-compose\.yml);make build && make restart;auto"
    "Dependencias de PHP;^composer\.(json|lock)$;make composer c=install;auto"
    "Front (dependencias, CSS o JS);^(package(-lock)?\.json$|resources/(css|js)/|vite\.config\.js$);make assets;auto"
    "Migraciones;^database/migrations/;make migrate;auto"
    "Código de la app;^app/;make queue-restart;auto"
    "Seeders y factories;^database/(seeders|factories)/;make fresh (borra los datos de desarrollo): ejecútalo tú si quieres los datos de prueba nuevos;aviso"
    "Variables de entorno;^\.env\.example$;compara tu .env con .env.example y añade las variables nuevas;aviso"
)

if [ -n "$FICHEROS" ]; then
    total=$(printf '%s\n' "$FICHEROS" | wc -l | tr -d ' ')
    echo "${BOLD}Desde $desde_corto han cambiado $total ficheros${OFF}"
else
    echo "${BOLD}Desde $desde_corto no ha cambiado ningún fichero${OFF}"
fi

ejecutados=(); fallido=''; avisos=(); pendientes=()

# Comprobación fija, no depende del pull: sin el enlace public/storage las fotos
# de perfil dan 403 (#89). make setup lo crea, pero los entornos anteriores no lo tienen.
# -L y no -e: el enlace apunta a la ruta del contenedor (/var/www/html/…), que en tu equipo no existe
if [ ! -L public/storage ]; then
    echo
    echo "${BOLD}Enlace public/storage${OFF} ${DIM}(no existe: las fotos subidas no se verían)${OFF}"
    echo "    → make artisan c=storage:link"
    if [ -z "$SOLO_VER" ]; then
        if make artisan c=storage:link >/dev/null 2>&1; then
            echo "    ${OK}✓${OFF} hecho"
            ejecutados+=("make artisan c=storage:link")
        else
            echo "    ${KO}✗${OFF} ha fallado: prueba a mano con make artisan c=storage:link"
            fallido="make artisan c=storage:link"
        fi
    fi
fi
for regla in "${REGLAS[@]}"; do
    IFS=';' read -r nombre patron comando modo <<< "$regla"
    coinciden=$(printf '%s\n' "$FICHEROS" | grep -E "$patron" || true)
    [ -z "$coinciden" ] && continue

    n=$(printf '%s\n' "$coinciden" | wc -l | tr -d ' ')
    echo
    echo "${BOLD}$nombre${OFF} ${DIM}($n)${OFF}"
    printf '%s\n' "$coinciden" | head -3 | sed "s/^/    ${DIM}/; s/\$/${OFF}/"
    [ "$n" -gt 3 ] && echo "    ${DIM}… y $((n - 3)) más${OFF}"

    if [ "$modo" = aviso ]; then
        echo "    ${AVISO}⚠${OFF} $comando"
        avisos+=("$nombre: $comando")
        continue
    fi

    if [ -n "$SOLO_VER" ]; then
        echo "    → $comando"
        continue
    fi
    if [ -n "$fallido" ]; then
        echo "    ${DIM}→ $comando (sin ejecutar: antes ha fallado otro)${OFF}"
        pendientes+=("$comando")
        continue
    fi

    echo "    → $comando"
    log=$(mktemp)
    if bash -c "$comando" >"$log" 2>&1; then
        echo "    ${OK}✓${OFF} hecho"
        ejecutados+=("$comando")
    else
        echo "    ${KO}✗${OFF} ha fallado. Últimas líneas:"
        tail -15 "$log" | sed 's/^/      /'
        fallido="$comando"
    fi
    rm -f "$log"
done

echo
echo "${BOLD}Resumen${OFF}"
if [ -n "$SOLO_VER" ]; then
    echo "  Solo lista (ver=1): no se ha ejecutado nada. Sin ver=1, make post-pull ejecuta los comandos con →."
fi
for c in "${ejecutados[@]+"${ejecutados[@]}"}"; do echo "  ${OK}✓${OFF} $c"; done
if [ -n "$fallido" ]; then
    echo "  ${KO}✗${OFF} $fallido: arréglalo y vuelve a lanzar make post-pull (lo ya hecho no hace daño repetirlo)"
fi
for c in "${pendientes[@]+"${pendientes[@]}"}"; do echo "  ${DIM}·${OFF} $c ${DIM}(pendiente)${OFF}"; done
for a in "${avisos[@]+"${avisos[@]}"}"; do echo "  ${AVISO}⚠${OFF} a mano · $a"; done
if [ -z "$SOLO_VER" ] && [ ${#ejecutados[@]} -eq 0 ] && [ -z "$fallido" ] && [ ${#avisos[@]} -eq 0 ]; then
    echo "  ${OK}✓${OFF} Nada que reconstruir: los cambios se ven al recargar la página."
fi

[ -z "$fallido" ]
