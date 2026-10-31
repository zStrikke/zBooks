# Revisión de la planificación desde el punto de vista de producto

- **Fecha:** 2026-10-31
- **Participantes:** PO, Claude
- **Contexto:** al terminar la issue #5, el PO preguntó qué faltaba en la planificación vista por un PM. Se guarda para retomarla más adelante.

## Lo que faltaba, por orden de importancia

### 1. El arranque en frío
zBooks es una red: un mapa vacío no sirve a nadie, y nadie libera libros si nadie los va a cazar. Propuestas:
- Lanzar en **una sola ciudad**.
- **Sembrar el mapa** con lugares que ya existen (bibliotecas libres, cafeterías dispuestas).
- Usar los **eventos como motor de captación**, no como un extra.

### 2. Validar antes de construir tanto
Faltó *discovery*: a quién va dirigido, qué alternativas usa hoy (BookCrossing, Little Free Library) y qué métrica dice que funciona.
- Métrica principal propuesta: **porcentaje de libros liberados que alguien captura**.
- El MVP real sería más pequeño: registrar, etiqueta, capturar y diario. Enseñarlo a 10 personas antes de añadir mapas y API.

### 3. El flujo principal es en móvil y con QR
Quien encuentra un libro está en la calle, con el móvil. La captura desde el QR de la etiqueta debería ser la pantalla más cuidada del producto. **El objeto físico también es producto:** diseño, tamaño y durabilidad de la etiqueta.

### 4. Privacidad de las ubicaciones
Publicar «Lucía liberó un libro aquí a las 8:10» cada día revela sus rutinas. Hace falta poder retrasar o difuminar esa información. También faltan las piezas legales: política de privacidad, términos, edad mínima, moderación y registro de tratamientos del RGPD.

### 5. Restricciones de terceros
- Los mapas de OpenStreetMap y el geocodificador Nominatim tienen **políticas de uso que prohíben el uso intensivo gratuito**. En producción hará falta otro proveedor o alojarlos.
- Email: dominio con SPF/DKIM para no acabar en spam.

### 6. Operación y medición
Monitorización de errores (Sentry), analítica respetuosa con la privacidad (Plausible o Umami), *feature flags* (Laravel Pennant) y backups **probados**, no solo hechos.

### 7. Proceso
Demo al final de cada iteración (*sprint review*), *Definition of Ready* para las historias y un registro de riesgos que se revise.

## Qué se decidió

| Punto | Decisión | Dónde |
|---|---|---|
| 1. Sembrar el mapa | Seeders de demo en Barcelona, después de las funciones geoespaciales | #34 (v0.5.0) |
| 1. Eventos como captación | Moderadores y admins crean eventos oficiales desde Filament | #35 (v0.9.0) |
| 3. QR | QR en la etiqueta y la ficha, a la vez que el ZBID | #32 (v0.3.0) |
| 4. Privacidad | Autores anónimos por defecto; la hora se guarda exacta, el libro aparece al momento y la hora se muestra en franjas | #33 (v0.4.0), ADR-0010 |
| 4. Privacidad | Amigos: ven el nombre del otro en el diario de los libros (préstamos e historial) | #37 (v0.10.0) |
| 2, 5, 6, 7 y parte legal del 4 | Aparcados por ahora | Retomar desde esta nota |

## Debate posterior: ¿retrasar la publicación?

- Se valoró publicar las liberaciones en lotes al cerrar cada franja, para que verlas aparecer no revelase la hora.
- Se descartó: el libro quedaría horas invisible y quien lo libera no lo vería aparecer en el mapa.
- La clave fue otra: **no mostrar públicamente quién libera ni quién captura**. Sin autor, la hora no permite seguir a nadie.
- De ahí salen los amigos: el nombre solo se ve entre amigos, para poder prestarse libros y ver el historial.

## Pendiente de retomar

- Métrica principal y *discovery* (punto 2).
- Proveedor de mapas y geocodificación para producción (punto 5). Conviene decidirlo antes de la épica #28.
- Piezas legales y RGPD (punto 4). También antes de la épica #28.
- Observabilidad y proceso (puntos 6 y 7).
