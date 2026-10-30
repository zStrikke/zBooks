# 07 · Despliegue (Docker Compose)

```mermaid
flowchart LR
    U(["👤 Navegador"])

    subgraph Host["Servidor (local o VPS)"]
        direction LR
        subgraph Compose["docker compose"]
            WEB["web<br/>nginx:alpine<br/>:80 / :443"]
            APP["app<br/>php:8.3-fpm<br/>Laravel + Livewire + Filament"]
            QUEUE["queue<br/>Horizon"]
            SCHED["scheduler<br/>schedule:work"]
            REVERB["reverb<br/>websockets (opcional)"]
            DB[("mysql:8")]
            REDIS[("redis")]
            MEILI[("meilisearch")]
            MAIL["mailpit<br/>(solo dev)"]
        end
    end

    subgraph Ext["Servicios externos"]
        OL["Open Library API"]
        OSM["OpenStreetMap<br/>tiles + Nominatim"]
        SMTP["SMTP<br/>(producción)"]
    end

    U -->|HTTPS| WEB
    U -.->|WSS| REVERB
    WEB -->|FastCGI| APP
    APP --> DB
    APP --> REDIS
    APP --> MEILI
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
- MySQL, Redis y Meilisearch usan **volúmenes** para persistir datos.
- En desarrollo, `node` (Vite) corre aparte para recompilar CSS/JS en caliente; no está en producción.
- Las llamadas a APIs externas van **por la cola** y con caché, para no depender de su disponibilidad.
