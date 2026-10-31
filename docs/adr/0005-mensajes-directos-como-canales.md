# ADR-0005: Mensajes directos como canales privados de dos miembros

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

La comunidad tendrá canales tipo Slack (públicos y privados) y también mensajes directos entre dos miembros. Ambos necesitan lo mismo: mensajes en Markdown, menciones, edición, contador de no leídos y, en el futuro, tiempo real.

## Opciones consideradas

1. **Dos sistemas:** tablas `channels`/`messages` y otras `conversations`/`direct_messages`.
2. **Un sistema:** un mensaje directo es un canal de tipo `direct` con exactamente dos miembros.

## Decisión

Elegimos **un único sistema**: `channels.type` puede ser `public`, `private` o `direct`.

- Un canal `direct` se crea al enviar el primer mensaje a alguien y no admite más miembros.
- Entre dos personas solo existe un canal `direct`.

## Consecuencias

**Positivas**
- Mensajes, no leídos, menciones y tiempo real se programan una sola vez.
- Menos tablas y menos código que mantener.

**Negativas / riesgos**
- Las reglas de cada tipo deben estar bien encapsuladas (quién crea, quién entra, si se puede invitar).
- Las consultas de «mis conversaciones» deben filtrar por tipo.

**Seguimiento**
- Policy de canal con tests por tipo.
- Índice o restricción para garantizar un único canal `direct` por pareja.

## Referencias

- RF-33 a RF-37.
- [Diagrama 06 · ER (Comunidad)](../diagramas/06-er.md)
