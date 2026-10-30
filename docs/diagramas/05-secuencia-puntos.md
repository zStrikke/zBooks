# 05 · Secuencia: otorgar puntos e insignias

Se dispara con cualquier evento de dominio que puntúa (`BookRegistered`, `BookReleased`, `BookCaught`, `ReviewPublished`, `EventAttendanceConfirmed`…). Ejemplo con `BookCaught`.

```mermaid
sequenceDiagram
    autonumber
    participant EV as Event bus
    participant Q as Worker (Horizon)
    participant AP as AwardPoints<br/>(Listener)
    participant R as PointRules<br/>(config)
    participant DB as PostgreSQL
    participant CB as CheckBadges<br/>(Listener)
    participant N as Notificaciones

    EV->>Q: BookCaught(book, catcher)
    Q->>AP: handle()
    AP->>R: reglas para "book_caught"
    R-->>AP: catcher +2 (1/ejemplar), registrador +5

    loop por cada beneficiario
        AP->>DB: ¿supera límite diario o ya puntuado?
        alt dentro del límite
            AP->>DB: INSERT point_transactions
            AP->>DB: UPDATE users.points_total / level
        else límite alcanzado
            AP-->>AP: se ignora (se registra en log)
        end
    end

    AP->>EV: dispatch PointsAwarded
    EV->>Q: CheckBadges
    Q->>CB: handle()
    CB->>DB: evaluar reglas de insignias
    opt insignia nueva
        CB->>DB: INSERT user_badges
        CB->>N: "¡Has ganado la insignia Trotamundos!"
    end
    opt sube de nivel
        CB->>N: "¡Ahora eres Bibliotecario!"
    end
```

## Puntos clave

- **Idempotencia:** si el worker reintenta el trabajo, no se duplican puntos (clave única por acción + sujeto + usuario).
- **Libro de cuentas inmutable:** nunca se edita una transacción; las correcciones son transacciones negativas.
- **Rankings:** no se calculan aquí sino con una tarea programada que agrega `point_transactions` del mes.
