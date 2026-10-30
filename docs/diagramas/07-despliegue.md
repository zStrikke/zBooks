# 07 · Despliegue (Docker Compose)

```mermaid
flowchart LR
    U(["👤 Navegador"])
    MOB(["📱 App móvil<br/>(futuro)"])

    subgraph Host["Servidor (local o VPS)"]
        direction LR
        subgraph Compose["docker compose"]
            WEB["web<br/>nginx:alpine<br/>:80 / :443"]
            APP["app<br/>php:8.3-fpm<br/>Laravel: web + API v1 + Filament"]
            QUEUE["queue<br/>Horizon"]
            SCHED["scheduler<br/>schedule:work"]
            REVERB["reverb<br/>websockets (opcional)"]
            DB[("postgis/postgis:16-3.4<br/>PostgreSQL + PostGIS")]
            REDIS[("redis")]
            MAIL["mailpit<br/>(solo dev)"]
        end
    end

    subgraph Ext["Servicios externos"]
        OL["Open Library API"]
        OSM["OpenStreetMap<br/>tiles + Nominatim"]
        SMTP["SMTP<br/>(producción)"]
    end

    U -->|HTTPS · sesión| WEB
    MOB -->|HTTPS · token Sanctum<br/>/api/v1| WEB
    U -.->|WSS| REVERB
    WEB -->|FastCGI| APP
    APP --> DB
    APP --> REDIS
    QUEUE --> REDIS
    QUEUE --> DB
    SCHED --> DB
    REVERB --> REDIS
    APP --> MAIL
    QUEUE --> MAIL
    QUEUE -->|producción| SMTP
    QUEUE --> OL
    QUEUE --> OSM
    U -->|tiles del mapa| OSM
```

## Notas

- `app`, `queue`, `scheduler` y `reverb` comparten la **misma imagen**; solo cambia el comando de arranque.
- PostgreSQL y Redis usan **volúmenes** para persistir datos.
- La búsqueda de texto usa el full-text de PostgreSQL; Meilisearch queda como opción futura si hiciera falta.
- Web y móvil entran por el mismo nginx: la web con sesión y cookies, el móvil con token Bearer en `/api/v1`.
- En desarrollo, `node` (Vite) corre aparte para recompilar CSS/JS en caliente; no está en producción.
- Las llamadas a APIs externas van **por la cola** y con caché, para no depender de su disponibilidad.
