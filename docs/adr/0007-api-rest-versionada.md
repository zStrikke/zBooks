# ADR-0007: API REST versionada construida junto a cada funcionalidad

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

El PO quiere llevar zBooks a una app móvil en el futuro y seguir las mejores convenciones de una API real. La web usa Livewire y no necesita API para sí misma.

## Opciones consideradas

1. **API junto a cada funcionalidad:** cada historia incluye web + API + tests desde la iteración 1.
2. **Iteración dedicada tras el MVP:** construir toda la API de golpe más adelante.
3. **Sin API** hasta que exista la app móvil.

## Decisión

Elegimos la **opción 1**: una API REST en **`/api/v1`**, construida junto a cada funcionalidad.

- **Dos puertas, un dominio:** los componentes Livewire y los controladores de la API llaman a las mismas *Actions*. Ninguna lógica de negocio vive en controladores ni componentes.
- **Versionado en la URL.** Un cambio incompatible crea `/api/v2`; la v1 se mantiene un tiempo con la cabecera `Sunset`.
- **Convenciones:**
  - Recursos en plural e inglés.
  - Respuestas con `data` / `meta` / `links` mediante API Resources.
  - Endpoints de mapa en **GeoJSON** (RFC 7946).
  - Errores en *Problem Details* (**RFC 9457**).
  - Paginación por cursor en listados largos.
  - Filtros y orden con `spatie/laravel-query-builder`.
  - Rate limiting por usuario.
- **Documentación** OpenAPI 3.1 generada con **Scramble** en `/docs/api`.
- **Tests de contrato** (Pest) por endpoint: estructura, códigos de estado y permisos.

## Consecuencias

**Positivas**
- La API está lista y probada cuando llegue la app móvil.
- Obliga a mantener la lógica en el dominio, lo que mejora el diseño.
- Práctica realista de diseño de APIs y documentación.

**Negativas / riesgos**
- Añade ~3 semanas a la planificación (de ~19 a ~22).
- Riesgo de duplicar validaciones entre formularios Livewire y FormRequests.

**Seguimiento**
- Compartir reglas de validación entre web y API.
- Revisar en cada retrospectiva si la API frena demasiado el avance.

## Referencias

- RF-47 a RF-51, RNF-10.
- [Diagrama 08 · Arquitectura web + API](../diagramas/08-arquitectura-web-api.md)
