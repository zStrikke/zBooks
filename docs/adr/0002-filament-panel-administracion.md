# ADR-0002: Panel de administración con Filament en lugar de AdminLTE

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

zBooks necesita un panel para gestionar usuarios, roles, libros, zonas, canales públicos, eventos, denuncias y la configuración de la gamificación. El PO conocía y le gustaba AdminLTE.

## Opciones consideradas

1. **AdminLTE:** plantilla basada en Bootstrap + jQuery; los CRUDs se programan a mano (controladores, vistas, formularios).
2. **Filament 3:** framework de paneles sobre Livewire + Tailwind; genera CRUDs, tablas, filtros y formularios a partir de los modelos.

## Decisión

Elegimos **Filament 3** en `/admin`.

- Usa el mismo stack que el resto de la app (ADR-0001): Tailwind y Livewire.
- AdminLTE traería Bootstrap y jQuery, que chocan con Tailwind y duplican estilos.
- Los CRUDs a mano ya se aprenden en la parte pública; en el admin prima la productividad.

## Consecuencias

**Positivas**
- Recursos de administración en horas, no en días.
- Integración con `spatie/laravel-permission` para roles y con las Policies existentes.

**Negativas / riesgos**
- Dependencia fuerte de un paquete con su propio ciclo de versiones mayores.
- Menos control sobre el aspecto visual que una plantilla hecha a mano.

**Seguimiento**
- Mantener la lógica de negocio en Actions: los recursos de Filament deben llamarlas, no reimplementarlas.

## Referencias

- RF-25, RF-26, RF-31.
- [Diagrama 08 · Arquitectura web + API](../diagramas/08-arquitectura-web-api.md)
