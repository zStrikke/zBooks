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
    RELEASED --> LOST : marcar perdido<br/>(sin capturas en X meses)

    CAUGHT --> ON_SHELF : el visitante se registra<br/>y lo reclama
    CAUGHT --> RELEASED : se libera de nuevo
    CAUGHT --> LOST : marcar perdido

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

- Solo el **poseedor actual** puede liberar, archivar o marcar como perdido (Policy).
- Una liberación **controlada** (a un destinatario) sigue siendo `RELEASED` hasta que el destinatario confirma la captura.
- Cada transición genera una `JournalEntry`. El estado actual es una caché de la última entrada relevante.

## Pendiente de validar (PO)

- [ ] ¿Pasados cuántos meses sin capturas se ofrece marcar un libro liberado como perdido?
- [ ] ¿Puede el **registrador original** (no poseedor) marcarlo como perdido?
