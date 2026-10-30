# Diagramas

Diagramas como código en [Mermaid](https://mermaid.js.org/). GitHub los renderiza directamente al abrir cada fichero.

| # | Diagrama | Tipo | Para qué sirve |
|---|---|---|---|
| 01 | [Casos de uso](01-casos-de-uso.md) | UML | Qué puede hacer cada actor |
| 02 | [Modelo de dominio](02-dominio.md) | UML (clases) | Entidades, responsabilidades y relaciones |
| 03 | [Estados del ejemplar](03-estados-ejemplar.md) | UML (estados) | Transiciones válidas de un libro |
| 04 | [Secuencia: capturar un libro](04-secuencia-captura.md) | UML (secuencia) | Flujo del caso de uso principal |
| 05 | [Secuencia: otorgar puntos](05-secuencia-puntos.md) | UML (secuencia) | Flujo de la gamificación |
| 06 | [Entidad-relación](06-er.md) | ER | Tablas, claves y relaciones de PostgreSQL + PostGIS |
| 07 | [Despliegue](07-despliegue.md) | UML (despliegue) | Contenedores Docker |
| 08 | [Arquitectura web + API](08-arquitectura-web-api.md) | Capas | Web y API v1 sobre el mismo dominio; convenciones de la API |
| 09 | [Secuencia: autenticación](09-secuencia-autenticacion.md) | UML (secuencia) | Registro, verificación por email, sesión web y tokens Sanctum |

## Convenciones

- Nombres de tablas y campos en inglés (como en el código); textos explicativos en español.
- Si cambia el diseño, se actualiza el diagrama **en el mismo PR** que el código.
- Al cerrar el MVP (v0.5.0) se generará el ER desde las migraciones para compararlo con el `06-er.md`.
