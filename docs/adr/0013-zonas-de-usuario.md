# ADR-0013: Zonas de usuario: radio o área, con la precisión que elija cada persona

- **Estado:** Aceptado
- **Fecha:** 2026-11-01
- **Decisores:** PO, Claude

## Contexto

Cada usuario tiene una zona de referencia para ver «libros cerca», recibir sugerencias de eventos y, más adelante, avisos. El análisis la definía como un punto con radio, y pedía que las ubicaciones de usuario fueran aproximadas (RNF-04). Guardar el punto exacto de casa es un riesgo de privacidad innecesario.

## Opciones consideradas

1. **Solo punto y radio:** simple, pero obliga a dar un punto.
2. **Solo barrio o ciudad:** muy privado, pero poco preciso en zonas grandes.
3. **Las dos formas, a elegir:** cada persona decide cuánto revela.

## Decisión

Opción **3**. La zona tiene dos modos:

| Modo | Cómo se indica | Qué se guarda |
|---|---|---|
| `radius` | Geolocalización del navegador o del móvil, o un punto en el mapa (su casa u otro cercano) | Punto **redondeado a ~250 m** + radio (2,5 km por defecto, de 0,5 a 25, en pasos de 500 m) |
| `area` | Un barrio o una ciudad del catálogo | Referencia al área; su contorno vive en `areas` |

- **`user_zones`** guarda `kind` (`home` | `temporary`), `mode`, `source` (`geolocation` | `map` | `area`) y, según el modo, `location` + `radius_m` o `area_id`. Una restricción `CHECK` impide mezclarlos.
- **`areas`** es el catálogo de barrios, distritos y ciudades con su contorno (`geography(MultiPolygon, 4326)`) y su área superior.
  - **Barcelona** se precarga desde Open Data BCN (distritos y barrios oficiales), para la demo.
  - **Otras ciudades** se traen de OpenStreetMap con Nominatim (`polygon_geojson=1`) la primera vez que alguien las elige; se simplifican y se guardan. Con caché y respetando su política de uso.
- **«Libros cerca»:** `ST_DWithin(punto, radio)` en el modo `radius` y `ST_Covers(contorno, punto)` en el modo `area`, detrás de un único método para que el resto del código no distinga.
- La zona **nunca** aparece en el perfil público ni en la API de otros usuarios.
- **Nombres:** `areas` (barrios y ciudades) no es lo mismo que las zonas ZBZ (`zones`, locales concretos donde se liberan libros).

**Entrega:** el modo `radius` en la v0.2.0 (#18); el modo `area` y el catálogo en la v0.3.0 (#54).

## Consecuencias

**Positivas**
- Cada persona elige cuánto revela; el sistema nunca guarda la dirección exacta.
- La demo de Barcelona tiene barrios oficiales desde el primer día.

**Negativas / riesgos**
- Una tabla más (`areas`) y dos caminos en las consultas de cercanía.
- Los contornos de OpenStreetMap pueden ser pesados: hay que simplificarlos al guardarlos.
- Datos de OpenStreetMap (licencia ODbL) y de Open Data BCN: hay que mostrar su atribución.

**Seguimiento**
- La zona temporal (#19, v0.3.0) usa los mismos dos modos.
- Revisar el redondeo de 250 m cuando haya datos reales: en zonas rurales puede ser poco.

## Revisión 2026-11-06

Una zona se puede **apagar sin borrarla** (#99): `is_active` en `user_zones`. Apagada, se conserva tal cual, pero «Libros cerca» y los avisos (v0.5.0) deben ignorarla. Guardar una zona la vuelve a encender.

## Revisión 2026-11-05

Al construir Mi zona (#18), el PO pidió dividir por dos las distancias: **redondeo de 250 m** (antes 500) y **radio de 0,5 a 25 km** con 2,5 km por defecto (antes 1 a 50 y 5). Una zona sirve para ver lo que hay cerca y decidir; 50 km es más que una ciudad entera. Ver libros de todas partes será otra pantalla, pensada para curiosear. El radio se guarda en metros (`radius_m`) para admitir medios kilómetros.

El redondeo se mantiene aunque nadie más vea la zona: protege ante filtraciones de la base de datos, accesos internos (admins, logs, copias) y funciones futuras que muestren distancias, que permitirían triangular un punto exacto. Además, el RGPD pide guardar solo lo necesario.

## Referencias

- RF-05 · RNF-04 · ADR-0006 · ADR-0010 · Issues #18, #19, #54
- [Diagrama ER](../diagramas/06-er.md)
