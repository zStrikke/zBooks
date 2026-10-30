# 04 · Secuencia: capturar un libro (CU-03)

Caso: un **miembro** encuentra un libro e introduce su BCID.

```mermaid
sequenceDiagram
    autonumber
    actor U as Miembro
    participant LW as Livewire<br/>CatchForm
    participant RL as RateLimiter
    participant P as BookPolicy
    participant A as CatchBook<br/>(Action)
    participant DB as MySQL
    participant EV as Event bus
    participant Q as Cola (Redis)

    U->>LW: introduce BCID
    LW->>RL: ¿intentos disponibles?
    RL-->>LW: ok
    LW->>DB: buscar Book por bcid
    alt BCID no existe
        DB-->>LW: null
        LW-->>U: error "BCID no encontrado"
    else BCID existe
        DB-->>LW: Book + diario
        LW-->>U: ficha del libro y formulario
        U->>LW: "Lo he encontrado" (lugar + comentario)
        LW->>P: can('catch', book)
        P-->>LW: permitido
        LW->>A: execute(book, user, data)
        activate A
        A->>DB: BEGIN
        A->>DB: INSERT journal_entries (CAUGHT)
        A->>DB: UPDATE books (status, current_holder_id)
        A->>DB: COMMIT
        A->>EV: dispatch BookCaught
        deactivate A
        EV->>Q: NotifyPreviousReaders
        EV->>Q: AwardPoints
        EV->>Q: CheckBadges
        LW-->>U: "¡Capturado!" + redirección al diario
    end
```

## Variante: visitante anónimo

Igual hasta el paso de captura, con estas diferencias:
- No hay `user`; la entrada se guarda con `is_anonymous = true` y el estado pasa a `CAUGHT`.
- No se otorgan puntos al visitante, **sí** al registrador (+5).
- Se muestra una invitación a registrarse para reclamar el libro.

## Puntos clave

- **Transacción:** diario y estado del libro se actualizan juntos o no se actualizan.
- **Lo lento va a la cola:** emails, puntos e insignias no retrasan la respuesta al usuario.
- **Rate limiting** antes de consultar la BD para dificultar que se adivinen BCIDs.
