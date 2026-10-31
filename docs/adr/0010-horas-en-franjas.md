# ADR-0010: Guardar la hora exacta de liberaciones y capturas, mostrarla solo en franjas

- **Estado:** Propuesto
- **Fecha:** 2026-10-31
- **Decisores:** PO, Claude

## Contexto

Cada liberación y captura tiene ubicación y hora. La ubicación tiene que ser bastante exacta, porque sirve para encontrar el libro. La hora no: publicar «liberado aquí a las 8:10» varias veces revela las rutinas de una persona (de dónde sale, por dónde pasa y cuándo).

Necesitamos la hora exacta internamente: ordenar el diario, calcular el recorrido, detectar abusos y aplicar la regla de los 12 meses de los libros perdidos.

## Opciones consideradas

1. **Guardar solo la fecha o una franja:** imposible filtrar, pero perdemos el orden exacto y la capacidad de investigar abusos.
2. **Guardar la hora exacta y no mostrarla nunca:** los datos completos se quedan dentro; la presentación (web y API) solo saca fecha y franja.
3. **Guardar la hora exacta y añadir ruido aleatorio al mostrarla:** más complejo y, con varias consultas, el ruido se puede promediar.

## Decisión

Elegimos la **opción 2**:
- `released_at` y `caught_at` se guardan exactos, en UTC.
- Se muestran como **fecha + franja** (madrugada 0–6 h, mañana 6–12 h, tarde 12–20 h, noche 20–24 h), en `app.display_timezone`. Pasados 30 días, solo la fecha.
- La API nunca devuelve la marca de tiempo: expone `released_on` y `released_period`.
- Un único formateador (value object) para Blade y los API Resources.
- La hora exacta solo es visible en el panel de administración, para moderación.

## Consecuencias

**Positivas**
- Se protegen las rutinas de quien libera sin perder datos.
- La regla vive en un solo sitio y un test la vigila.

**Negativas / riesgos**
- Cualquier campo nuevo que lleve hora (notificaciones, feeds, `updated_at` en la API, IDs secuenciales) puede filtrarla. Hay que revisarlo en cada PR.
- **El propio momento de aparición filtra la hora:** si el libro sale en el mapa justo al liberarlo, quien mire en tiempo real sabe cuándo se dejó.

**Seguimiento**
- Decidir antes de la v0.4.0 si una liberación se publica con un **retraso** (p. ej. de 1 a 3 horas). A cambio, «Libros cerca» se actualiza más tarde.
- Valorar si quien libera puede elegir difuminar también la ubicación en zonas sensibles (cerca de su casa).

## Referencias

- Issue #33 · Épica #21
- [Revisión de la planificación](../notas/2026-10-31-revision-planificacion.md), punto 4
