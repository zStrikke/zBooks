# 02 · Modelo de dominio

Vista de clases a nivel de código: modelos Eloquent con sus atributos principales y métodos de dominio. Se omiten timestamps, ids y relaciones triviales de Laravel.

```mermaid
classDiagram
    direction LR

    class User {
        +string username
        +string email
        +int pointsTotal
        +Level level
        +activeZone() UserZone
        +shelf() Collection~Book~
    }

    class UserZone {
        +string label
        +Point location
        +int radiusKm
        +ZoneType type
        +date endsAt
        +isExpired() bool
    }

    class Work {
        +string isbn
        +string title
        +string author
        +string language
        +averageRating() float
    }

    class Book {
        +string bcid
        +BookStatus status
        +string condition
        +release(ReleaseData) Release
        +catchBy(User?) JournalEntry
        +journey() Collection~JournalEntry~
        +journeyLine() LineString
        +distanceTravelledKm() float
    }

    class Release {
        +ReleaseType type
        +Point location
        +string hint
        +datetime releasedAt
    }

    class JournalEntry {
        +JournalEntryType type
        +string body
        +bool isAnonymous
    }

    class Review {
        +int rating
        +string body
    }

    class Zone {
        +string name
        +string address
        +Point location
        +bool approved
    }

    class PointTransaction {
        +string action
        +int points
    }

    class Badge {
        +string code
        +string name
        +isEarnedBy(User) bool
    }

    class Post {
        +string title
        +string slug
        +string bodyMd
        +PostStatus status
    }

    class Channel {
        +string name
        +ChannelType type
        +canBeCreatedBy(User) bool
    }

    class Message {
        +string bodyMd
        +datetime editedAt
    }

    class Event {
        +string title
        +datetime startsAt
        +int capacity
        +EventVisibility visibility
        +join(User) EventAttendee
        +hasFreeSpots() bool
    }

    class EventAttendee {
        +AttendeeStatus status
    }

    User "1" --> "*" UserZone : tiene
    UserZone "*" --> "0..1" Zone : OBCZ favorita
    Work "1" --> "*" Book : ejemplares
    Work "1" --> "*" Review
    User "1" --> "*" Review : escribe
    User "1" --> "*" Book : registra
    User "0..1" --> "*" Book : posee actualmente
    Book "1" --> "*" Release
    Book "1" --> "*" JournalEntry
    Release "*" --> "0..1" Zone
    User "1" --> "*" PointTransaction
    User "*" --> "*" Badge : gana
    User "1" --> "*" Post
    User "*" --> "*" Channel : miembro
    Channel "1" --> "*" Message
    User "1" --> "*" Event : organiza
    Event "1" --> "*" EventAttendee
    EventAttendee "*" --> "1" User
    Event "*" --> "0..1" Zone
```

## Enums (PHP 8.1+)

```mermaid
classDiagram
    direction LR
    class BookStatus {
        <<enumeration>>
        ON_SHELF
        RELEASED
        CAUGHT
        ARCHIVED
        LOST
    }
    class ReleaseType {
        <<enumeration>>
        WILD
        CONTROLLED
        ZONE
    }
    class JournalEntryType {
        <<enumeration>>
        REGISTERED
        RELEASED
        CAUGHT
        NOTE
    }
    class ZoneType {
        <<enumeration>>
        HOME
        TEMPORARY
    }
    class ChannelType {
        <<enumeration>>
        PUBLIC
        PRIVATE
        DIRECT
    }
    class EventVisibility {
        <<enumeration>>
        OPEN
        INVITE_ONLY
    }
    class AttendeeStatus {
        <<enumeration>>
        INVITED
        GOING
        WAITLIST
        DECLINED
        ATTENDED
    }
    class PostStatus {
        <<enumeration>>
        DRAFT
        PUBLISHED
    }
```

## Decisiones reflejadas

- **Obra vs ejemplar:** `Work` es el título (ISBN) y `Book` cada copia física con su BCID. Las reseñas cuelgan de `Work`; el diario, de `Book`.
- **Puntos como libro de cuentas:** `PointTransaction` es inmutable y `User.pointsTotal` es un total cacheado que siempre se puede recalcular (ADR-0004).
- **Geometrías:** `Point` y `LineString` son objetos de valor de PostGIS (paquete `clickbar/laravel-magellan`), no pares de floats.
- **Lógica en el dominio:** métodos como `Book::release()` o `Event::join()` delegarán en clases *Action* para mantener los modelos delgados.
