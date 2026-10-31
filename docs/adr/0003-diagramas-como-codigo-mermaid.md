# ADR-0003: Diagramas como código con Mermaid

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

El PO quiere diagramas UML y ER para diseñar la aplicación. Los diagramas suelen quedarse desactualizados porque viven fuera del repositorio, en herramientas gráficas.

## Opciones consideradas

1. **Herramienta gráfica** (draw.io, Lucidchart): exporta imágenes; difícil de revisar en un PR.
2. **PlantUML:** muy completo en UML; GitHub no lo renderiza sin servicios externos.
3. **Mermaid:** texto dentro de Markdown; GitHub lo renderiza de forma nativa.

## Decisión

Elegimos **Mermaid**, en ficheros Markdown dentro de `docs/diagramas/`.

## Consecuencias

**Positivas**
- Los cambios en diagramas se revisan en el PR como cualquier otro texto (diff línea a línea).
- Se renderizan directamente en GitHub.
- Se pueden validar automáticamente con el parser de Mermaid.

**Negativas / riesgos**
- Mermaid no tiene diagrama de casos de uso nativo: se simula con `flowchart`.
- Menos control del diseño visual que una herramienta gráfica.

**Seguimiento**
- Regla: si cambia el diseño, el diagrama se actualiza **en el mismo PR** que el código.
- Al cerrar el MVP se generará el ER desde las migraciones para compararlo con el diseño.
- Opcional: validar los diagramas en la CI.

## Referencias

- [Índice de diagramas](../diagramas/README.md)
