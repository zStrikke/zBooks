# Diseño

Maquetas de las pantallas antes de programarlas. Son la **referencia visual**, no el código: cada pantalla se construye después en Blade y Livewire con Tailwind, dentro de su issue.

## Cómo se trabaja

1. Las pantallas se diseñan en un **lienzo de diseño** (equivalente a Figma), donde se comentan y se ajustan.
2. Cuando están aprobadas, se guardan aquí, por versión:
   - **`capturas/`:** imágenes PNG, para verlas en GitHub o enlazarlas en una issue o un PR;
   - **`html/`:** cada pantalla exportada como HTML estático. Se abre en el navegador y permite consultar colores, tamaños y textos exactos.
3. La issue que construye la pantalla enlaza su diseño. Si al programarla algo cambia, se actualiza el diseño o se explica el motivo en el PR.

Criterios comunes: **mobile-first** (el móvil se diseña primero), los colores y tipografías de la portada (`resources/css/app.css`) y la accesibilidad básica (etiquetas en los campos, contraste suficiente y zonas táctiles de 44 px como mínimo).

## Versiones

| Versión | Pantallas | Lienzo | Issues |
|---|---|---|---|
| [v0.2.0](v0.2.0/) | Registro, iniciar sesión, verificar email y perfil público (móvil y escritorio) | [Abrir el lienzo](https://claude.ai/artifact/QBqaDvURaFcYWre4CRTsUz) (privado: hay que compartirlo para que otros lo vean) | #10, #11, #16 |

### v0.2.0

| Pantalla | Móvil | Escritorio | Issue |
|---|---|---|---|
| Registro | [captura](v0.2.0/capturas/movil-registro.png) · [html](v0.2.0/html/movil-registro.html) | [captura](v0.2.0/capturas/escritorio-registro.png) · [html](v0.2.0/html/escritorio-registro.html) | #10 |
| Iniciar sesión | [captura](v0.2.0/capturas/movil-login.png) · [html](v0.2.0/html/movil-login.html) | [captura](v0.2.0/capturas/escritorio-login.png) · [html](v0.2.0/html/escritorio-login.html) | #11 |
| Verifica tu email | [captura](v0.2.0/capturas/movil-verificar-email.png) · [html](v0.2.0/html/movil-verificar-email.html) | [captura](v0.2.0/capturas/escritorio-verificar-email.png) · [html](v0.2.0/html/escritorio-verificar-email.html) | #10 |
| Perfil público | [captura](v0.2.0/capturas/movil-perfil-publico.png) · [html](v0.2.0/html/movil-perfil-publico.html) | [captura](v0.2.0/capturas/escritorio-perfil-publico.png) · [html](v0.2.0/html/escritorio-perfil-publico.html) | #16 |

**Ideas de cada pantalla en escritorio:** la tarjeta de viaje con sellos (registro), la etiqueta troquelada con el buscador de código (login), el matasellos (verificación) y el carné de socio (perfil). Las cifras, lugares y textos entre corchetes son datos de ejemplo.
