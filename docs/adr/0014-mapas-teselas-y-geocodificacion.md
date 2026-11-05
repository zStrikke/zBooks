# ADR-0014: Mapas: Leaflet, teselas de OpenStreetMap y Nominatim desde el servidor

- **Estado:** Aceptado
- **Fecha:** 2026-11-05
- **Decisores:** PO, Claude

## Contexto

Mi zona (#18) es el primer mapa de zBooks: el miembro elige un punto y un radio, y puede buscar una dirección. Hace falta una librería de mapas, unas teselas (las imágenes del mapa) y un buscador de direcciones (geocodificación). Más adelante vendrán «Libros cerca», el viaje de cada libro y los eventos.

## Opciones consideradas

**Librería:** Leaflet (ligera, sin cuenta) o MapLibre GL (mapas vectoriales, más pesada).

**Teselas:** las de OpenStreetMap (gratis, sin cuenta, pero su [política](https://operations.osmfoundation.org/policies/tiles/) no admite tráfico de producción) o un proveedor con plan gratuito (MapTiler, Stadia, Thunderforest…), que pide registro y clave.

**Direcciones:** Nominatim llamado desde el navegador, o desde nuestro servidor.

## Decisión

- **Leaflet**, instalado con npm y cargado **solo en la página que tiene mapa** (import dinámico): las demás páginas no pagan sus ~45 KB.
- **Teselas de OpenStreetMap para desarrollo y la demo**, configurables con `MAP_TILES_URL` y `MAP_TILES_ATTRIBUTION`. Antes de producción (v1.0.0) se cambia a un proveedor con plan gratuito: es cambiar dos variables de entorno.
- **Nominatim desde el servidor** (`App\Geo\Nominatim`), para cumplir su [política de uso](https://operations.osmfoundation.org/policies/nominatim/):
  - un User-Agent que identifica a zBooks (`NOMINATIM_USER_AGENT`);
  - **como mucho una petición por segundo** entre todos los usuarios;
  - respuestas en caché 7 días;
  - y, por usuario, 30 búsquedas por minuto.
- La atribución de OpenStreetMap se ve siempre en el mapa.

## Consecuencias

**Positivas**
- Nada de cuentas ni claves para desarrollar; el navegador del usuario no habla con Nominatim.
- Cambiar de proveedor de teselas no toca el código.

**Negativas / riesgos**
- Con mucho uso a la vez, el límite de una búsqueda por segundo hará esperar a algunos usuarios. Se acepta para la demo; si crece, se cambia a un geocodificador con plan propio.
- Las teselas de OpenStreetMap no se pueden usar en producción: queda como tarea de la v1.0.0.

**Seguimiento**
- Antes de la v1.0.0: proveedor de teselas para producción y revisar el geocodificador.

## Referencias

- ADR-0006 · ADR-0012 · ADR-0013 · Issue #18
