# Cambios pendientes para la próxima versión del análisis

Lista de lo que cambia respecto al [análisis funcional vigente](analisis-funcional.md) (v1.5). Se va llenando durante las iteraciones y se vacía al publicar la siguiente versión: entonces pasa a la página «Cambios en la versión X» del documento.

Cada línea indica la sección afectada y la decisión que lo justifica (ADR, issue o diario).

## Pendientes

- **2. Alcance / 5. RF-05 (zona):** el usuario elige la precisión: geolocalización, punto en el mapa (redondeado a ~500 m) o barrio/ciudad del catálogo. ADR-0013 · #18 · #54.
- **10. Modelo de datos:** `user_zones` con `kind`, `mode` y `source`; nueva tabla `areas`. ADR-0013 · diagrama 06.
- **11. Stack:** Fortify con pantallas propias en lugar de Breeze; `spatie/laravel-permission` y `laravel-magellan` confirmados. ADR-0012.
- **15. Planificación:** #14, #17 y #19 pasan a la v0.3.0; la #43 se divide (base en español en la v0.2.0; inglés, catalán y selector en la v0.3.0, #53); zona por barrio o ciudad en la v0.3.0 (#54). Diario #018.
- **1. Objetivos / nuevo apartado:** métrica principal del producto: **libros capturados al mes**. Diario #018.
- **5. RF-01:** reglas de username (minúsculas, únicos sin distinguir mayúsculas, nombres reservados, no editable de momento) y contraseña estándar de Laravel. #10.
