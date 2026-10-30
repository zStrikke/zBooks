# 09 · Secuencia: registro, verificación y acceso (web y API)

Una misma cuenta, dos formas de identificarse (ADR-0008): **sesión** en la web y **token Sanctum** en la API.

## Registro y verificación por email

```mermaid
sequenceDiagram
    autonumber
    actor U as Usuario
    participant C as Web (Breeze)<br/>o API /auth/register
    participant A as CreateUser<br/>(Action)
    participant DB as PostgreSQL
    participant Q as Cola
    participant MAIL as Email

    U->>C: username, email, contraseña
    C->>A: execute(datos validados)
    A->>DB: INSERT users (email_verified_at = null)
    A-->>C: usuario creado
    C->>Q: evento Registered
    Q->>MAIL: enlace firmado de verificación (caduca en 60 min)
    C-->>U: web: sesión iniciada, aviso "verifica tu email"<br/>API: 201 + token
    U->>MAIL: abre el enlace
    MAIL->>C: GET /email/verify/{id}/{hash}?signature=…
    C->>DB: UPDATE users SET email_verified_at = now()
    C-->>U: "Email verificado ✔"

    Note over U,C: Sin verificar puede leer, pero las acciones<br/>que escriben (registrar, liberar, publicar…)<br/>devuelven 403 por el middleware `verified`.
```

## Acceso desde la API (móvil)

```mermaid
sequenceDiagram
    autonumber
    participant APP as App móvil
    participant API as /api/v1
    participant S as Sanctum
    participant DB as PostgreSQL

    APP->>API: POST /auth/token {email, password, device_name}
    API->>DB: comprobar credenciales (throttle: 5/min)
    alt credenciales válidas
        API->>S: createToken(device_name, abilities)
        S->>DB: INSERT personal_access_tokens (hash)
        API-->>APP: 201 {token} (texto plano solo esta vez)
    else no válidas
        API-->>APP: 422 Problem Details
    end

    APP->>API: GET /me/shelf<br/>Authorization: Bearer <token>
    API->>S: validar token y abilities
    S->>DB: buscar hash, actualizar last_used_at
    API-->>APP: 200 {data: [...]}

    APP->>API: DELETE /auth/token
    API->>DB: borrar ese token
    API-->>APP: 204
```

## Notas

- La web **no** usa Sanctum: Livewire en el mismo dominio funciona con la sesión estándar de Laravel.
- Desde su perfil, el usuario ve sus dispositivos conectados y puede revocar cualquier token.
- Los tokens caducan (p. ej. 90 días sin uso) para limitar daños si se pierde un móvil.
