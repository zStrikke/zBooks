# ADR-0001: Stack base Laravel + Livewire + Docker, sin mezclar Vue

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

zBooks es un proyecto personal y de aprendizaje que debe funcionar de verdad. El PO propuso Laravel, MySQL, Docker y «Vue/Livewire». La interfaz es sobre todo formularios, listados, fichas y algún mapa: poca interactividad compleja.

## Opciones consideradas

1. **Livewire 3 + Alpine.js:** componentes reactivos escritos en PHP; todo vive en Laravel.
2. **Vue 3 + Inertia.js:** experiencia tipo SPA con componentes Vue; requiere más JavaScript.
3. **Mezclar Livewire y Vue:** usar cada uno según la pantalla.

## Decisión

Elegimos **Laravel 12 + Livewire 3 + Alpine.js + Tailwind CSS**, empaquetado con **Docker Compose**. No se mezcla con Vue.

- Livewire cubre bien formularios y listados sin construir una API para la propia web.
- Mezclar dos frameworks de frontend duplica patrones, dependencias y conocimiento necesario.
- La interactividad puntual (mapas Leaflet, reproductor del viaje) se resuelve con Alpine.js.

## Consecuencias

**Positivas**
- Un solo lenguaje dominante (PHP) y un solo flujo de trabajo.
- Encaja con Filament (ADR-0002), que también es Livewire + Tailwind.
- Entorno idéntico en local y producción gracias a Docker.

**Negativas / riesgos**
- Menos práctica con un framework JS moderno.
- Las interacciones muy ricas (mapa animado) necesitan JS a mano con Alpine.

**Seguimiento**
- La app móvil futura no depende de esta decisión: consumirá la API (ADR-0007).
- La base de datos inicial (MySQL) se sustituyó por PostgreSQL + PostGIS en el ADR-0006.

## Referencias

- Análisis funcional v1.4, sección 11.
- [Diagrama 07 · Despliegue](../diagramas/07-despliegue.md)
