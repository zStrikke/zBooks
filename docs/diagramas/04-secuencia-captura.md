# 04 · Secuencia: capturar un libro (CU-03)

Caso: un **miembro** encuentra un libro e introduce su ZBID.

```mermaid
sequenceDiagram
    autonumber
    actor U as Miembro
    participant LW as Livewire<br/>CatchForm
    participant RL as RateLimiter
    participant P as BookPolicy
    participant A as CatchBook<br/>(Action)
    participant DB as PostgreSQL
    participant EV as Event bus
    participant Q as Cola (Redis)

    U->>LW: introduce ZBID
    LW->>RL: ¿intentos disponibles?
    RL-->>LW: ok
    LW->>DB: buscar Book por zbid
    alt ZBID no existe
        DB-->>LW: null
        LW-->>U: error "ZBID no encontrado"
    else ZBID existe
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

## Casos típicos relatados

### 1. La captura habitual
Marcos ve un libro con una etiqueta de zBooks en el metro. En casa entra en la web e introduce el ZBID (pasos 1–4). Aparecen la ficha y el diario: el libro viene de Lucía y ya ha pasado por dos lectores (pasos 7–8). Pulsa «Lo he encontrado», indica «metro, línea 3» y escribe un comentario (paso 9). La acción `CatchBook` guarda la entrada de diario y cambia el poseedor en una sola transacción (pasos 12–16). Marcos ve «¡Capturado!» al instante. Unos segundos después, en segundo plano, Lucía y los lectores anteriores reciben un email (paso 18); Marcos suma +2 puntos y Lucía +5 (paso 19).

### 2. El ZBID mal tecleado
Elena escribe el ZBID con un dígito cambiado. La búsqueda no encuentra nada y la web le muestra «ZBID no encontrado» (pasos 5–6). Lo intenta varias veces más. Al quinto fallo en un minuto, el `RateLimiter` responde que no quedan intentos (pasos 2–3) y le pide esperar. Así nadie puede probar códigos al azar para «cazar» libros ajenos.

### 3. Capturar dos veces el mismo libro
Marcos, ya con el libro en su estantería, vuelve a introducir el ZBID por error. La `BookPolicy` detecta que ya es el poseedor y deniega la captura (pasos 10–11). La web le muestra el libro con el botón «Liberar» en lugar de «Lo he encontrado». No se duplica la entrada de diario ni los puntos.

## Variante: API móvil

`POST /api/v1/books/{zbid}/catches` sigue el mismo camino desde el paso de autorización: el *FormRequest* sustituye al formulario Livewire, el controlador llama a la **misma** `CatchBook` y responde `201` con la entrada de diario como *API Resource*.

## Variante: visitante anónimo

Igual hasta el paso de captura, con estas diferencias:
- No hay `user`; la entrada se guarda con `is_anonymous = true` y el estado pasa a `CAUGHT`.
- No se otorgan puntos al visitante, **sí** al registrador (+5).
- Se muestra una invitación a registrarse para reclamar el libro.

## Puntos clave

- **Transacción:** diario y estado del libro se actualizan juntos o no se actualizan.
- **Lo lento va a la cola:** emails, puntos e insignias no retrasan la respuesta al usuario.
- **Rate limiting** antes de consultar la BD para dificultar que se adivinen ZBIDs.
