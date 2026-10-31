# ADR-0009: Laravel 13 y PHP 8.4 en lugar de Laravel 12 y PHP 8.3

- **Estado:** Aceptado
- **Fecha:** 2026-10-31
- **Decisores:** PO, Claude
- **Modifica:** las versiones indicadas en ADR-0001

## Contexto

El análisis funcional y el ADR-0001 fijaron Laravel 12 y PHP 8.3, que eran las versiones más recientes que Claude conocía al redactarlos. Al empezar la iteración 0 se comprobó en Packagist que:

- La versión estable actual es **Laravel 13** (v13.39, octubre de 2026).
- Todos los paquetes previstos son compatibles con Laravel 13: Livewire 4, Filament 5, Sanctum 4, Scramble, laravel-magellan, spatie/laravel-permission, spatie/laravel-query-builder, Horizon y Larastan.
- **Pest 5** (versión actual) exige **PHP 8.4**.

## Opciones consideradas

1. **Mantener Laravel 12 + PHP 8.3**, como estaba documentado. Empezaría el proyecto con una versión que pronto deja de recibir correcciones y obligaría a usar versiones antiguas de Pest.
2. **Laravel 13 + PHP 8.4.** Versiones estables actuales, compatibles con todo el stack.
3. **Laravel 13 + PHP 8.5.** Más reciente, pero con más riesgo de que algún paquete no lo soporte aún.

## Decisión

Elegimos la **opción 2**: **Laravel 13** sobre **PHP 8.4**, con las versiones mayores actuales de los paquetes: Livewire 4, Filament 5 y Pest 5.

## Consecuencias

**Positivas**
- El proyecto arranca con soporte largo por delante.
- Acceso a las novedades de PHP 8.4 (property hooks, visibilidad asimétrica).

**Negativas / riesgos**
- Algunos ejemplos de los libros y tutoriales usan versiones anteriores y habrá que adaptarlos.
- Livewire 4 y Filament 5 pueden diferir de lo que se encuentre en documentación antigua.

**Seguimiento**
- Revisar las versiones mayores al empezar cada iteración relevante (p. ej. Filament en la It. 5).

## Referencias

- ADR-0001 (stack base).
- [Política de soporte de Laravel](https://laravel.com/docs/releases).
