# ADR-0011: Idiomas: código y API en inglés, web en el idioma de cada usuario

- **Estado:** Aceptado
- **Fecha:** 2026-11-01
- **Decisores:** PO, Claude

## Contexto

La web va dirigida a personas, y las primeras demos son en Barcelona: hace falta español, catalán e inglés. La API la consumen programas (la futura app móvil y otros clientes), que esperan un contrato estable y estándar.

Hasta ahora todo estaba en español, también los mensajes de error de la API.

## Opciones consideradas

**Cómo elegir el idioma de la web**
1. **Prefijo en la URL con la misma ruta** (`/es/books/...`, `/en/books/...`), como MDN o Apple. Es lo que recomienda Google para el SEO multiidioma.
2. **Prefijo con rutas traducidas** (`/es/libros`, `/en/books`), como IKEA. Lo más caro de mantener.
3. **Una sola URL por recurso y el idioma como preferencia** (perfil, cookie, navegador), como Facebook, X o Reddit con sesión.

**Paquete:** `mcamara/laravel-localization` (soporta Laravel 13) está pensado para las opciones 1 y 2. Sin prefijo solo puede quedarse el idioma por defecto.

## Decisión

**Código en inglés:** `APP_LOCALE=en` y `APP_FALLBACK_LOCALE=en`. Los textos se escriben en inglés en el código (`__('Search a book')`) y se traducen en `lang/es.json` y `lang/ca.json`. Si falta una traducción, sale en inglés.

**API siempre en inglés.**
- Como el idioma base es `en`, la API no necesita middleware: solo la web cambia de idioma.
- Los textos de Problem Details están fijos en inglés en el código.
- La documentación de `/docs/api` también va en inglés.
- Los datos que guardan los usuarios (títulos, reseñas, nombres de zonas) se devuelven tal cual, en su idioma original.
- Un test comprueba que la API sigue en inglés aunque exista la traducción española y el cliente envíe `Accept-Language: es`.

**Web: opción 3, una URL por recurso.**
- Idiomas: `es`, `en` y `ca`.
- Un middleware en el grupo `web` elige el idioma en este orden:
  1. preferencia del perfil (`users.locale`);
  2. cookie (el selector de idioma para invitados);
  3. `Accept-Language`, el primero de la lista que tengamos;
  4. **español**, el idioma de reserva (`app.web_fallback_locale`).
- Se elige español como reserva porque el público inicial está en España y el rastreador de Google, que no suele enviar `Accept-Language`, indexará la web en ese idioma.
- Sin paquete: un middleware propio de pocas líneas basta.
- Filament usa la misma preferencia del usuario.

**Código:** sigue la convención actual: clases, rutas y textos fuente en inglés; tests y comentarios en español.

## Consecuencias

**Positivas**
- El contrato de la API es igual para cualquier cliente y sigue la costumbre del sector.
- Rutas simples, sin prefijos, y el QR (`/c/{ZBID}`) no necesita ningún caso especial.
- Un enlace compartido se abre en el idioma de quien lo recibe.

**Negativas / riesgos**
- Google indexa la web en un solo idioma (español). Se acepta: lo que la gente busca es el contenido de los libros, que no se traduce.
- Cada texto de la web se traduce a tres idiomas. Un test avisará si falta alguna clave.
- Hasta la #43, las vistas tienen textos en español con `<html lang="en">`.
- Si la app móvil necesita mensajes traducidos, los traducirá ella con el código o el `type` del error.

**Seguimiento**
- #42: pasar la API a inglés (v0.1.0).
- #43: web multiidioma y textos de las vistas en inglés con traducciones (v0.2.0, con el perfil de usuario).
- Si algún día el SEO por idioma importa, se puede pasar a la opción 1 sin cambiar los textos.

## Referencias

- ADR-0007 (API versionada) · Issues #42 y #43
- [mcamara/laravel-localization en Laraplugins](https://laraplugins.io/plugins/mcamara/laravel-localization)
