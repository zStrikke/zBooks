# Análisis funcional

| Fichero | Qué es |
|---|---|
| [`analisis-funcional.md`](analisis-funcional.md) | **Fuente** de la versión vigente (v1.5). Se lee bien en GitHub |
| `zBooks_Analisis_Funcional_vX.Y.pdf` | Cada versión publicada, generada desde la fuente. Las antiguas se conservan |
| [`cambios-pendientes.md`](cambios-pendientes.md) | Lo que ha cambiado desde la versión vigente y aún no está en el documento |
| `herramientas/` | Script y estilos para generar el PDF |

## Cuándo sale una versión nueva

Al cerrar una iteración, si los cambios pendientes son relevantes (alcance, requisitos, stack o planificación). Los cambios pequeños esperan a la siguiente.

- **Versión menor (1.5 → 1.6):** cambios de peso en el mismo producto.
- **Versión mayor (1.x → 2.0):** cambia el alcance de fondo.

## Cómo publicar una versión

1. Pasar los cambios pendientes al documento y a su página «Cambios en la versión X», con referencias a cada ADR o issue.
2. Subir `version` y `fecha` en la cabecera y añadir la fila al historial de versiones.
3. `make analisis` genera `zBooks_Analisis_Funcional_vX.Y.pdf` (necesita pandoc y Node con Playwright).
4. Vaciar `cambios-pendientes.md`.
