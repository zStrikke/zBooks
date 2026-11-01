# ADR-0011: Idiomas: web en español, inglés y catalán; API en inglés

- **Estado:** Aceptado
- **Fecha:** 2026-11-01
- **Decisores:** PO, Claude

## Contexto

La web va dirigida a personas, y las primeras demos son en Barcelona: hace falta español, catalán e inglés. La API la consumen programas (la futura app móvil y otros clientes), que esperan un contrato estable y estándar.

Hasta ahora todo estaba en español, también los mensajes de error de la API.

## Opciones consideradas

**Cómo elegir el idioma de la web**
1. **Prefijo en la URL** (`/es/...`, `/en/...`, `/ca/...`).
2. **Sesión, cookie o preferencia del usuario**, con las mismas URL para todos.
3. **Solo `Accept-Language`** del navegador.

**Idioma de la API**
1. **Según `Accept-Language`**, como la web.
2. **Siempre en inglés.**

## Decisión

**Web: prefijo en la URL**, con español por defecto.
- Idiomas: `es` (por defecto), `en` y `ca`.
- Una ficha compartida se abre en el mismo idioma, y Google indexa cada idioma por separado (`hreflang`).
- `/` redirige según la preferencia del usuario, la cookie o `Accept-Language`, en ese orden; si no hay ninguna, a `/es`.
- La preferencia se guarda en el perfil (`users.locale`).
- **Sin prefijo:** la URL corta del QR (`/c/{ZBID}`), que redirige al idioma del visitante para que un QR impreso no cambie nunca; `/up`, y el panel de Filament, que usa el idioma del usuario.

**API: siempre en inglés.**
- Un middleware fija el locale `en` en el grupo `api`, sin mirar `APP_LOCALE` ni `Accept-Language`.
- Los textos de Problem Details están escritos en inglés en el código, porque un 404 sin ruta no pasa por el middleware.
- La documentación de `/docs/api` también va en inglés.
- Los datos que guardan los usuarios (títulos, reseñas, nombres de zonas) se devuelven tal cual, en su idioma original.

**Código:** sigue la convención actual: clases y rutas en inglés; vistas, tests y comentarios en español.

## Consecuencias

**Positivas**
- El contrato de la API es igual para cualquier cliente y sigue la costumbre del sector.
- Los enlaces de la web llevan el idioma, así que se pueden compartir e indexar.

**Negativas / riesgos**
- Cada texto de la web se traduce a tres idiomas. Un test avisará si falta alguna clave.
- Todas las rutas públicas llevan el prefijo: hay que acordarse al crear enlaces (`route()` con el locale).
- Si la app móvil necesita mensajes traducidos, los traducirá ella con el código o el `type` del error.

**Seguimiento**
- #42: pasar la API a inglés (v0.1.0).
- #43: web multiidioma (v0.2.0, con el perfil de usuario).
- Valorar `mcamara/laravel-localization` frente a un middleware propio al empezar la #43.

## Referencias

- ADR-0007 (API versionada) · Issues #42 y #43
