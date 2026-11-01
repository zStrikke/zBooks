# Cambios desde el análisis funcional v1.4

El [análisis funcional v1.4](zBooks_Analisis_Funcional_v1.4.pdf) se mantiene como foto del diseño inicial. Esta página recoge lo que ha cambiado después, sección por sección. Cada nota al pie[^ejemplo] lleva a la decisión que lo justifica.

**Última revisión:** 2026-11-01, cierre de la v0.1.0.

## 2. Alcance

- **Amigos** entre usuarios: solicitar, aceptar y deshacer la amistad, en la v0.10.0[^amigos]. Más adelante, bloquear usuarios.
- **Código QR** junto al ZBID, en la etiqueta y en la ficha del ejemplar, en la v0.3.0[^qr].
- **Datos de demostración en Barcelona** para enseñar el mapa, en la v0.5.0[^demo].
- **Eventos oficiales** creados por moderadores y admins desde Filament, en la v0.9.0[^eventos].

## 5. Requisitos funcionales

- **RF-04 (privacidad):** se concreta. Quién registra, libera o captura un libro es anónimo en público, salvo entre amigos. La hora se guarda exacta, pero en público solo se ve la fecha y la franja (madrugada, mañana, tarde o noche)[^privacidad].
- **RF-45 (interfaz en español e inglés):** se amplía con el **catalán** y se adelanta de la v0.10.0 a la **v0.2.0**. El idioma es una preferencia del usuario, con una sola URL por recurso[^idiomas].

## 8. Requisitos no funcionales

- **RNF-04 (RGPD):** el consentimiento y las páginas legales se adelantan a la v0.2.0, antes del registro[^rgpd]. Exportar y borrar la cuenta siguen en la v1.0.0.
- **RNF-06 (mantenibilidad):** se añade **PHP Insights**, con un mínimo de 90 en cada categoría y comprobado en la CI[^calidad].
- **RNF-10 (API):** la API responde **siempre en inglés**, errores incluidos[^idiomas].
- **Nuevo, observabilidad:** logs diarios que se guardan 7 días y errores enviados a Slack, en la v1.0.0[^logs].

## 11. Stack tecnológico

| En la v1.4 | Ahora | Por qué |
|---|---|---|
| Laravel 12 (PHP 8.3+) | **Laravel 13, PHP 8.4** | Salió entre el análisis y el arranque[^laravel] |
| Livewire 3 | **Livewire 4** | Versión actual al arrancar[^front] |
| Tailwind | **Tailwind 4** | Versión actual al arrancar; se configura en el CSS[^front] |
| Pest, Larastan, Pint | **+ PHP Insights** | Puntuación del código en la CI[^calidad] |

## 12. Arquitectura y entorno Docker

- Los tests usan su propia base de datos, `zbooks_testing`; la de desarrollo (`zbooks`) no se toca nunca[^calidad].

## 14. Proceso de trabajo

- **Entornos:** el análisis decía «sin staging por ahora». Se añade un **entorno de preproducción** a la épica de producción, con copias de producción anonimizadas[^staging].
- `master` exige los dos checks de la CI (calidad y tests) para fusionar[^ci].

## 15. Planificación por iteraciones

- **v0.1.0** suma dos issues que no estaban previstas: la API en inglés[^idiomas] y el mantenimiento de la CI[^ci].
- **v0.2.0** suma la web multiidioma[^idiomas] y el RGPD mínimo[^rgpd].
- **v0.10.0** pierde la traducción, que pasa a la v0.2.0[^idiomas].

## 19. Backlog futuro

- «Entorno de staging» deja de ser futuro: pasa a la épica de producción[^staging].

[^ejemplo]: Así: el número lleva a la nota y la flecha de la nota vuelve al texto.
[^amigos]: Issue [#37](https://github.com/zStrikke/zBooks/issues/37) y [ADR-0010](../adr/0010-privacidad-ciclo-del-libro.md).
[^qr]: Issue [#32](https://github.com/zStrikke/zBooks/issues/32) y [revisión de la planificación](../notas/2026-10-31-revision-planificacion.md).
[^demo]: Issue [#34](https://github.com/zStrikke/zBooks/issues/34).
[^eventos]: Issue [#35](https://github.com/zStrikke/zBooks/issues/35).
[^privacidad]: [ADR-0010](../adr/0010-privacidad-ciclo-del-libro.md) e issue [#33](https://github.com/zStrikke/zBooks/issues/33).
[^idiomas]: [ADR-0011](../adr/0011-idiomas-web-y-api.md) e issues [#42](https://github.com/zStrikke/zBooks/issues/42) y [#43](https://github.com/zStrikke/zBooks/issues/43).
[^rgpd]: Issue [#48](https://github.com/zStrikke/zBooks/issues/48) y [cierre de la iteración 0](../notas/2026-11-01-cierre-iteracion-0.md).
[^calidad]: Issue [#6](https://github.com/zStrikke/zBooks/issues/6) y diario #011.
[^logs]: Issue [#49](https://github.com/zStrikke/zBooks/issues/49).
[^laravel]: [ADR-0009](../adr/0009-laravel-13-php-84.md).
[^front]: Issue [#5](https://github.com/zStrikke/zBooks/issues/5) y diario #009.
[^staging]: Épica [#28](https://github.com/zStrikke/zBooks/issues/28) y diario #011.
[^ci]: Issues [#7](https://github.com/zStrikke/zBooks/issues/7) y [#46](https://github.com/zStrikke/zBooks/issues/46).
