# ADR-0010: Privacidad en el ciclo del libro: anonimato por defecto y horas en franjas

- **Estado:** Propuesto
- **Fecha:** 2026-10-31
- **Decisores:** PO, Claude

## Contexto

Cada liberación y captura tiene ubicación, hora y autor. La ubicación tiene que ser bastante exacta, porque sirve para encontrar el libro. Pero si se publican juntos el autor, el lugar y la hora, cualquiera puede reconstruir las rutinas de una persona.

Necesitamos todos los datos internamente: ordenar el diario, calcular el recorrido, moderar y aplicar la regla de los 12 meses de los libros perdidos.

## Opciones consideradas

1. **Retrasar la publicación** (en lotes al cerrar cada franja o con un retraso aleatorio). Protege la hora, pero el libro queda invisible horas y quien lo libera no lo ve aparecer en el mapa.
2. **Publicar al momento y mostrar la hora solo en franjas.** Quien mire en ese instante deduce la hora, pero no sirve de nada si no sabe quién liberó el libro.
3. **Hacer anónimo al autor de cada liberación y captura.** Rompe el vínculo entre la persona y el lugar, que es el riesgo real.

## Decisión

Combinamos la **2** y la **3**.

**Anonimato por defecto**
- La ficha del libro, el diario y el mapa no muestran quién registró, liberó o capturó el libro. Muestran «un usuario» o «un invitado».
- **Excepción, los amigos:** cada entrada del diario muestra el nombre de su autor a sus amigos (amistad aceptada por las dos partes).
  - El propietario ve qué amigo tiene o tuvo su libro.
  - Quien captura un libro ve si es de un amigo.
  - Así se pueden prestar libros entre amigos y ver el historial.
- La amistad revela también el historial anterior. Al deshacerla, se vuelve a ocultar.
- El perfil público y los rankings muestran cifras («23 libros liberados»), nunca qué libros.

**Horas en franjas**
- `released_at` y `caught_at` se guardan exactos, en UTC.
- El libro aparece en el mapa **al momento**.
- En la parte pública (web y API) solo se muestran la **fecha y la franja**:
  - madrugada (0–6 h), mañana (6–12 h), tarde (12–18 h) y noche (18–24 h);
  - en `app.display_timezone`;
  - pasados 30 días, solo la fecha.
- La API nunca devuelve la marca de tiempo: expone `released_on` y `released_period`.

**Panel de administración:** moderadores y admins ven todo (autor y hora exacta).

**Implementación:** la regla vive en un solo sitio, no repartida por las vistas. Un value object para las franjas, y una Policy o servicio que decide si quien mira puede ver al autor. Lo usan tanto Blade como los API Resources.

## Consecuencias

**Positivas**
- Se protegen las rutinas sin perder datos ni la inmediatez: quien libera ve su libro en el mapa al momento.
- Los amigos añaden valor (préstamos, historial compartido) sin exponer a nadie más.

**Negativas / riesgos**
- Cualquier campo nuevo puede romper el anonimato: notificaciones, feeds, `updated_at` o IDs de usuario en la API, enlaces del perfil. Hay que revisarlo en cada PR, y un test lo vigila.
- Quien consulta la API en el momento de una liberación deduce la hora. Se acepta, porque sin autor no permite identificar a nadie. El throttle de la API limita el abuso.
- Hasta que existan los amigos (v0.10.0), todo es anónimo para todos.

**Seguimiento**
- Bloquear a un usuario, más adelante: un bloqueado no podrá enviar solicitudes de amistad.
- Valorar difuminar la ubicación en zonas sensibles, por ejemplo cerca de casa.

## Referencias

- Issues #33 y #37 · Épicas #21 y #27 · Historia #16 (perfil público)
- [Revisión de la planificación](../notas/2026-10-31-revision-planificacion.md), punto 4
