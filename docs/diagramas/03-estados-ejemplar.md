# 03 · Estados del ejemplar (`BookStatus`)

```mermaid
stateDiagram-v2
    direction LR
    [*] --> ON_SHELF : registrar

    ON_SHELF --> RELEASED : liberar (wild / OBCZ / controlada)
    ON_SHELF --> ARCHIVED : archivar
    ON_SHELF --> LOST : marcar perdido

    RELEASED --> ON_SHELF : capturado por un miembro<br/>(pasa a su estantería)
    RELEASED --> CAUGHT : capturado por un visitante anónimo
    RELEASED --> LOST : registrador lo marca perdido<br/>(12 meses sin actividad)

    CAUGHT --> ON_SHELF : el visitante se registra<br/>y lo reclama
    CAUGHT --> RELEASED : se libera de nuevo
    CAUGHT --> LOST : registrador lo marca perdido<br/>(12 meses sin actividad)

    LOST --> ON_SHELF : capturado por un miembro
    LOST --> CAUGHT : capturado anónimamente

    ARCHIVED --> ON_SHELF : desarchivar
    ARCHIVED --> [*]
```

## Significado de cada estado

| Estado | Significado | Poseedor (`current_holder_id`) |
|---|---|---|
| `ON_SHELF` | En la estantería de un miembro, no liberado | El miembro |
| `RELEASED` | Liberado, esperando a ser encontrado (o en camino a un destinatario) | `null` |
| `CAUGHT` | Encontrado por alguien **sin cuenta** | `null` (desconocido) |
| `LOST` | Se da por perdido; se puede recuperar si alguien lo captura | `null` |
| `ARCHIVED` | Retirado por su poseedor (deteriorado, se lo queda…) | El miembro |

## Reglas

- Solo el **poseedor actual** puede liberar o archivar (Policy).
- **Marcar como perdido:**
  - Desde `ON_SHELF`: el poseedor actual, en cualquier momento.
  - Desde `RELEASED` o `CAUGHT`: el **registrador original**, cuando han pasado **12 meses** sin entradas de diario.
  - Una tarea programada avisa al registrador cuando su libro cumple esos 12 meses.
- Una liberación **controlada** (a un destinatario) sigue siendo `RELEASED` hasta que el destinatario confirma la captura.
- Cada transición genera una `JournalEntry`. El estado actual es una caché de la última entrada relevante.

## Decisiones del PO

- [x] Un libro liberado se puede marcar como perdido tras **12 meses** sin actividad.
- [x] Lo puede marcar el **registrador original**, aunque ya no sea el poseedor.
