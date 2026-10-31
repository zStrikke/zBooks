# ADR-0004: Puntos de gamificación como transacciones inmutables

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

La gamificación otorga puntos por acciones (registrar, liberar, capturar, reseñar, asistir a eventos), con límites diarios y reglas que pueden cambiar. Los puntos se calculan en segundo plano, en una cola que puede reintentar trabajos. Hay riesgo de trampas y de errores difíciles de auditar.

## Opciones consideradas

1. **Contador en `users.points`:** se suma o resta directamente.
2. **Libro de cuentas:** cada movimiento es una fila en `point_transactions`; el total es la suma.

## Decisión

Elegimos un **libro de cuentas inmutable** (`point_transactions`) con un total cacheado en `users.points_total`.

- Cada transacción guarda usuario, acción, puntos, sujeto (polimórfico) y una `idempotency_key` única.
- Las filas nunca se editan ni se borran: las correcciones son transacciones negativas.
- La tabla solo tiene `created_at` (sin `updated_at`).

## Consecuencias

**Positivas**
- **Auditable:** se sabe por qué tiene cada usuario sus puntos.
- **Idempotente:** si un trabajo se reintenta, la clave única impide duplicar puntos.
- **Recalculable:** el total se puede reconstruir si cambia una regla o hay un error.
- Los rankings mensuales salen de agregar transacciones por fecha.

**Negativas / riesgos**
- La tabla crece mucho más que un contador.
- Hay que mantener sincronizado el total cacheado.

**Seguimiento**
- Comando de mantenimiento para recalcular `points_total`.
- Índices por `(user_id, created_at)` cuando se implementen los rankings.

## Referencias

- RF-27 a RF-31, RNF-09.
- [Diagrama 05 · Secuencia de puntos](../diagramas/05-secuencia-puntos.md)
