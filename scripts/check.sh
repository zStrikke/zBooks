#!/usr/bin/env bash
# Ejecuta las mismas comprobaciones que la CI y resume el resultado.
# Uso: make check   (también lo ejecuta el hook de pre-push)

set -u

if [ -t 1 ]; then
    OK=$'\033[32m'; KO=$'\033[31m'; DIM=$'\033[2m'; BOLD=$'\033[1m'; OFF=$'\033[0m'
else
    OK=''; KO=''; DIM=''; BOLD=''; OFF=''
fi

RUN="docker compose exec -T app"
LOG_DIR=$(mktemp -d)
trap 'rm -rf "$LOG_DIR"' EXIT

# nombre | comando | cómo arreglarlo
STEPS=(
    "Estilo (Pint)|$RUN composer lint|make format"
    "Análisis estático (Larastan)|$RUN composer analyse -- --no-progress|corrige los errores de tipos que se muestran arriba"
    "Calidad (PHP Insights)|$RUN composer insights|revisa las puntuaciones por debajo de 90 y los avisos de seguridad (composer audit)"
    "Tests (Pest)|$RUN composer test|make test c=\"--filter=nombre\" para repetir solo el que falla"
)

failed=()
hints=()
inicio=$(date +%s)

echo "${BOLD}Comprobaciones antes de subir${OFF}"
for step in "${STEPS[@]}"; do
    IFS='|' read -r name cmd hint <<<"$step"
    log="$LOG_DIR/$(echo "$name" | tr -c 'a-zA-Z' '_').log"
    t0=$(date +%s)
    printf '  %s%*s' "$name" $((32 - ${#name})) ''
    if $cmd >"$log" 2>&1; then
        printf '%s✓%s %s%ss%s\n' "$OK" "$OFF" "$DIM" "$(($(date +%s) - t0))" "$OFF"
    else
        printf '%s✗%s %s%ss%s\n' "$KO" "$OFF" "$DIM" "$(($(date +%s) - t0))" "$OFF"
        failed+=("$name")
        hints+=("$hint")
        {
            echo
            echo "${KO}━━ $name ━━${OFF}"
            # PHP Insights añade enlaces de patrocinio al final: no aportan nada.
            grep -vE 'nunomaduro|Star or contribute|Sponsor the maintainers|Subscribe to my YouTube|^Script .* (handling|was called)' "$log"
        } >>"$LOG_DIR/fallos.txt"
    fi
done

if [ ${#failed[@]} -eq 0 ]; then
    printf '\n%s✓ Todo en verde%s (%ss). Puedes subir.\n' "$OK" "$OFF" "$(($(date +%s) - inicio))"
    exit 0
fi

cat "$LOG_DIR/fallos.txt"
printf '\n%s✗ Fallan %s de %s comprobaciones:%s\n' "$KO" "${#failed[@]}" "${#STEPS[@]}" "$OFF"
for i in "${!failed[@]}"; do
    printf '  • %s → %s\n' "${failed[$i]}" "${hints[$i]}"
done
printf '\nSi aun así quieres subir (por ejemplo, trabajo a medias): git push --no-verify\n'
exit 1
