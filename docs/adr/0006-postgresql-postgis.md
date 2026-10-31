# ADR-0006: PostgreSQL + PostGIS en lugar de MySQL

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

El núcleo de zBooks es geográfico: dónde se libera un libro, quién lo encuentra cerca, qué ruta sigue y cuántos kilómetros recorre. La versión inicial del diseño usaba MySQL 8. El PO tiene experiencia previa con datos geoespaciales y PostGIS, y quiere preparar la app para un cliente móvil.

## Opciones consideradas

1. **MySQL 8:** tipos espaciales básicos y `ST_Distance_Sphere`; pocas funciones geográficas.
2. **PostgreSQL 16 + PostGIS 3:** estándar de facto para datos geoespaciales.

## Decisión

Elegimos **PostgreSQL 16 + PostGIS 3** (imagen `postgis/postgis:16-3.4`).

- Las coordenadas se guardan como `geography(Point, 4326)`: WGS84 y distancias en metros.
- Índices **GiST** en todas las columnas `location`.
- En Laravel se usa `clickbar/laravel-magellan` para los tipos y funciones PostGIS.

| Necesidad | Función PostGIS |
|---|---|
| Libros cerca | `ST_DWithin` |
| Distancia | `ST_Distance` |
| Ruta del recorrido | `ST_MakeLine` |
| Km recorridos | `ST_Length` |
| Salida para mapa y API | `ST_AsGeoJSON` |

Además, la búsqueda de texto usa el **full-text de PostgreSQL** (`tsvector` + `unaccent` + índice GIN) en lugar de Meilisearch.

## Consecuencias

**Positivas**
- Consultas geográficas correctas y rápidas, resueltas en la base de datos.
- GeoJSON directo para Leaflet y para la API (ADR-0007).
- Un servicio menos (Meilisearch) que mantener.
- Extras de PostgreSQL: `jsonb`, índices parciales, restricciones `CHECK`.

**Negativas / riesgos**
- Curva de aprendizaje de PostGIS y diferencias de SQL frente a MySQL.
- Los tests necesitan PostgreSQL + PostGIS (no sirve SQLite en memoria).

**Seguimiento**
- Encapsular las consultas geográficas en scopes o servicios con tests.
- Revisar con `EXPLAIN` las consultas de cercanía en la iteración 4.
- Meilisearch queda en el backlog por si el full-text se queda corto.

## Referencias

- RNF-11, RF-15 a RF-21, RF-51.
- [Diagrama 06 · ER](../diagramas/06-er.md)
