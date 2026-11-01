---
title: "zBooks – Análisis funcional y planificación"
version: "1.5"
fecha: "01/11/2026"
lang: es
---

# zBooks {.unnumbered .unlisted .portada}

Clon de BookCrossing con Laravel

**Análisis funcional y planificación por iteraciones**

| | |
|---|---|
| Documento | Análisis funcional + Plan de entregas |
| Versión | 1.5 |
| Repositorio | github.com/zStrikke/zBooks |
| Fecha | 01/11/2026 |
| Stack | Laravel 13 · PHP 8.4 · PostgreSQL 16 + PostGIS · Docker · Livewire 4 + Alpine.js · Tailwind 4 · Filament · API REST v1 (Sanctum) |

## Historial de versiones {.unnumbered .unlisted}

| Versión | Fecha | Cambios |
|---|---|---|
| 1.0 | 30/10/2026 | Versión inicial: requisitos, modelo de datos, stack y 9 iteraciones. |
| 1.1 | 30/10/2026 | Gamificación, reseñas, blog personal, canales, eventos, zona de referencia del usuario, importación Goodreads, visualización del recorrido, admin con Filament, fase de diseño (UML/ER), proceso de trabajo y replanificación a 12 iteraciones. |
| 1.2 | 30/10/2026 | Nombre definitivo del proyecto: zBooks. Repositorio público en GitHub. |
| 1.3 | 30/10/2026 | PostgreSQL + PostGIS en lugar de MySQL; API REST versionada desde la It. 1 (preparación para app móvil); autenticación web por sesión + tokens Sanctum; verificación de email obligatoria; búsqueda con full-text de PostgreSQL; replanificación a ~22 semanas. |
| 1.4 | 30/10/2026 | Nomenclatura propia: BCID pasa a ZBID y OBCZ pasa a ZBZ (Zona zBooks). |
| **1.5** | **01/11/2026** | **Cierre de la v0.1.0: privacidad del ciclo, idiomas, amigos, QR en la ficha, RGPD mínimo, observabilidad, preproducción y stack actualizado. Detalle en la página siguiente.** |

# Cambios en la versión 1.5 {.unnumbered .unlisted .cambios}

Cambios respecto a la v1.4, por sección. Cada número volado remite a la decisión (ADR) o a la issue que lo justifica, en las referencias del final.

**2. Alcance**

- **Amigos:** solicitar, aceptar y deshacer la amistad, en la v0.10.0<sup>1</sup>. Más adelante, bloquear usuarios.
- **Código QR también en la ficha del ejemplar** y URL corta `/c/{ZBID}`, en la v0.3.0. Hasta ahora el QR solo iba en la etiqueta<sup>2</sup>.
- **Datos de demostración en Barcelona** para enseñar el mapa, en la v0.5.0<sup>3</sup>.
- **Eventos oficiales** creados por moderadores y admins desde Filament, en la v0.9.0<sup>4</sup>.
- **Idiomas:** español, inglés y catalán, adelantados a la v0.2.0<sup>5</sup>.

**5. Requisitos funcionales**

- **RF-03 y RF-04 (perfil y privacidad):** quien registra, libera o captura es anónimo en público, salvo entre amigos. La hora se guarda exacta, pero en público solo se ve la fecha y la franja. El perfil público muestra cifras, nunca qué libros<sup>6</sup>.
- **RF-09 (QR):** también en la ficha, con URL corta<sup>2</sup>.
- **RF-45 (idiomas):** se amplía con el catalán y pasa de la iteración 9 a la 1. Una sola URL por recurso; el idioma es una preferencia<sup>5</sup>.
- **RF-45** sube de prioridad: de S a M.
- **Nuevos:** RF-52 (amigos)<sup>1</sup> y RF-53 (eventos oficiales)<sup>4</sup>.

**10. Modelo de datos**

- Se proponen `friendships`, `users.privacy_accepted_at` y `events.is_official`. Se concretan al refinar cada iteración y entonces se actualiza el diagrama ER.

**8. Requisitos no funcionales**

- **RNF-04 (RGPD):** el consentimiento y las páginas legales se adelantan a la v0.2.0, antes del registro<sup>7</sup>.
- **RNF-06 (mantenibilidad):** se añade PHP Insights, con un mínimo de 90 en cada categoría<sup>8</sup>.
- **RNF-10 (API):** la API responde siempre en inglés<sup>5</sup>.
- **Nuevo, RNF-12 (observabilidad):** logs diarios de 7 días y errores a Slack<sup>9</sup>.

**11 y 12. Stack y Docker**

- Laravel 13 y PHP 8.4 (antes 12 y 8.3)<sup>10</sup>; Livewire 4 y Tailwind 4 (antes 3)<sup>11</sup>; Node 22 (antes 20).
- PHP Insights en la calidad<sup>8</sup>; `chillerlan/php-qrcode` en lugar de `simple-qrcode`<sup>2</sup>.
- El kit de autenticación (la v1.4 decía Breeze) se decide en el refinamiento de la v0.2.0.

**14. Proceso de trabajo**

- **Preproducción:** se añade a la épica de producción, con copias anonimizadas. La v1.4 decía «sin staging»<sup>12</sup>.
- `master` exige la CI para fusionar<sup>13</sup>. Los tests usan su propia base de datos, `zbooks_testing`<sup>8</sup>.
- **Idioma del código:** inglés en clases, rutas y textos fuente; español en tests, comentarios y documentación<sup>5</sup>.

**15. Planificación**

- **It. 0:** se suman la API en inglés y el mantenimiento de la CI. Duró 2 días en lugar de 1 semana.
- **It. 1:** se suman la web multiidioma y el RGPD mínimo.
- **It. 9:** pierde la traducción.
- **It. 10:** se suman la preproducción y la observabilidad.
- **Calendario:** el hito H4 cae en la semana 19, no en la 18 (error de cálculo de la v1.4).

**Referencias**

1. Issue #37 · ADR-0010.
2. Issue #32 · Revisión de la planificación (2026-10-31).
3. Issue #34.
4. Issue #35.
5. ADR-0011 · Issues #42 y #43.
6. ADR-0010 · Issue #33.
7. Issue #48.
8. Issue #6 · Diario #011.
9. Issue #49.
10. ADR-0009.
11. Issue #5 · Diario #009.
12. Épica #28 · Diario #011.
13. Issues #7 y #46.

# Índice {.unnumbered .unlisted .indice}

1. Introducción y objetivo
2. Alcance
3. Actores y roles
4. Glosario
5. Requisitos funcionales
6. Gamificación
7. Comunidad: blog, canales y eventos
8. Requisitos no funcionales
9. Casos de uso principales
10. Modelo de datos
11. Stack tecnológico
12. Arquitectura y entorno Docker
13. Diagramas (fase de diseño)
14. Proceso de trabajo
15. Planificación por iteraciones
16. Calendario resumen
17. Definición de terminado (DoD)
18. Riesgos y mitigaciones
19. Backlog futuro

# Introducción y objetivo

El proyecto consiste en desarrollar una aplicación web inspirada en BookCrossing.com: una plataforma que convierte el mundo en una «biblioteca global». Los usuarios registran sus libros, obtienen un identificador único (ZBID), los etiquetan y los «liberan» en lugares públicos o los intercambian con otros miembros. Quien encuentra el libro introduce el ZBID y deja una entrada en su diario, de modo que se puede seguir el recorrido de cada ejemplar indefinidamente.

Es un proyecto personal y no comercial, con un doble objetivo: que funcione y que sirva para aprender, simulando con la mayor fidelidad posible el proceso de un proyecto real.

**Objetivos**

- Ciclo completo del libro: registrar, etiquetar, liberar, capturar, diario y visualización del viaje.
- Comunidad: espacio personal (blog), canales de conversación, eventos/quedadas, reseñas y amigos.
- Motivación mediante gamificación sin incentivar trampas.
- Privacidad por defecto: el ciclo del libro no revela quién libera ni quién captura.
- Construcción incremental con un entregable funcional y una versión etiquetada por iteración.
- API REST versionada y documentada, preparada para una futura app móvil.
- Aprovechar PostGIS para búsquedas por cercanía, rutas y distancias.
- Buenas prácticas: tests, CI, Docker, documentación, ADRs, diario de desarrollo.

# Alcance

**MVP – versión 0.5.0 (iteraciones 0–4)**

- Cada funcionalidad se entrega en web y en la API v1, con sus tests.
- Usuarios con verificación de email, perfil y zona de referencia; tokens para la API.
- Web en español, inglés y catalán; aviso legal y política de privacidad.
- Estantería, registro por ISBN, ZBID, etiqueta PDF con QR y QR en la ficha del ejemplar.
- Liberar, capturar (también anónimo) y diario, con autores anónimos y horas en franjas.
- Visualización del recorrido, búsqueda, libros cerca, zonas ZBZ, notificaciones.
- Datos de demostración en Barcelona.

**Versión 1.0.0 (iteraciones 5–10)**

- Panel de administración (Filament) y moderación; reseñas y valoraciones.
- Gamificación: puntos, insignias, niveles y rankings.
- Blog personal y canales (incluye mensajes directos).
- Eventos/quedadas, también oficiales desde el panel; importación desde Goodreads; wishlist y seguimiento; amigos; estadísticas.
- Puesta en producción, con preproducción y observabilidad.

**Fuera de alcance**

- App móvil (la API la deja preparada; se abordará en otro proyecto).
- Pagos, membresías o venta de libros.
- Me gusta y comentarios en el blog (a estudiar en el futuro).

# Actores y roles

| Actor | Descripción | Permisos principales |
|---|---|---|
| Visitante | Persona sin cuenta, normalmente alguien que ha encontrado un libro. | Ver libros, diarios, blogs, eventos públicos y zonas; capturar un libro (entrada anónima); registrarse. |
| Miembro | Usuario registrado y verificado. | Estantería, libros, diarios, reseñas, blog, canales privados, eventos, wishlist, importación, amigos. |
| Moderador | Miembro de confianza. | Crear canales públicos y eventos oficiales, moderar contenido, aprobar zonas, gestionar denuncias. |
| Administrador | Gestor de la plataforma. | Todo lo anterior + usuarios, roles, configuración de gamificación. Ve autor y hora exacta del ciclo. |
| Cliente API | App móvil futura, Postman o tests. | Lo mismo que el usuario al que pertenece el token, limitado por sus abilities. |
| Sistema | Procesos automáticos. | Emails, colas, puntos e insignias, rankings, fin de zona temporal, recordatorios. |

# Glosario

| Término | Definición |
|---|---|
| ZBID | zBooks ID. Código único que identifica un ejemplar físico. |
| Obra / Ejemplar | Obra = el libro como título (ISBN). Ejemplar = una copia física concreta con su ZBID. |
| Liberar (release) | Dejar un libro en un lugar público («wild») o entregarlo a alguien («controlada»). |
| Capturar (catch) | Encontrar un libro e introducir su ZBID para registrar el hallazgo. |
| Entrada de diario | Registro del viaje de un ejemplar: dónde se encontró, dónde se libera. |
| Franja | Parte del día que se muestra en público en lugar de la hora: madrugada (0–7 h), mañana (7–14 h), tarde (14–21 h) y noche (21–24 h). |
| Amigo | Miembro con amistad aceptada por las dos partes; ve el nombre del autor en el diario. |
| Reseña | Opinión y valoración (1–5) sobre una obra; se agrega entre todos sus ejemplares. |
| ZBZ | Zona zBooks: lugar fijo y oficial (cafetería, biblioteca) donde se liberan libros. |
| Zona de referencia | Área a la que está vinculado un usuario (donde vive o donde está de viaje). |
| Canal | Espacio de conversación tipo Slack, público o privado. |
| Reputación | Puntos acumulados por el usuario mediante la gamificación. |
| ADR | Architecture Decision Record: ficha que documenta una decisión técnica. |
| API v1 | Interfaz REST en /api/v1 que expone las mismas funciones que la web, siempre en inglés. |
| Token Sanctum | Credencial por dispositivo para usar la API; revocable. |
| PostGIS | Extensión de PostgreSQL para datos geográficos (puntos, rutas, distancias). |
| GeoJSON | Formato estándar (RFC 7946) para intercambiar datos geográficos en JSON. |

# Requisitos funcionales

Prioridad MoSCoW: M = Must, S = Should, C = Could. «It.» = iteración prevista.

| ID | Módulo | Requisito | Prio. | It. |
|---|---|---|---|---|
| RF-01 | Usuarios | Registro con username, email y contraseña (web y API). Verificación de email obligatoria para cualquier acción que escriba datos. Aceptación de la política de privacidad. | M | 1 |
| RF-02 | Usuarios | Login, logout y recuperación de contraseña. | M | 1 |
| RF-03 | Usuarios | Perfil público: avatar, bio y estadísticas en cifras (nunca qué libros); su página principal es el blog personal. | M | 1 |
| RF-04 | Usuarios | Privacidad y preferencias de notificación. Autores anónimos en el ciclo del libro salvo entre amigos; horas en franjas. | S | 3–4 |
| RF-05 | Usuarios | Zona de referencia: vincularse a una zona; cambiarla de forma permanente (mudanza) o temporal con fecha de fin (viaje), volviendo sola a la habitual. | M | 1 |
| RF-06 | Libros | Registrar un libro manualmente (título, autor, idioma, género, estado, portada). | M | 2 |
| RF-07 | Libros | Autocompletar por ISBN (Open Library / Google Books). | S | 2 |
| RF-08 | Libros | Generar ZBID único e inmutable. | M | 2 |
| RF-09 | Libros | Etiqueta imprimible en PDF con ZBID y QR; QR también en la ficha, con URL corta `/c/{ZBID}`. | M | 2 |
| RF-10 | Libros | Estantería con filtros; editar y archivar (el ZBID nunca se reutiliza). | M | 2 |
| RF-11 | Ciclo | Liberar: tipo (wild / controlada / ZBZ), lugar en mapa, fecha y pistas. | M | 3 |
| RF-12 | Ciclo | Capturar introduciendo el ZBID o escaneando el QR. | M | 3 |
| RF-13 | Ciclo | Captura anónima para visitantes con invitación a registrarse. | S | 3 |
| RF-14 | Ciclo | Diario cronológico del ejemplar y nuevas entradas por su poseedor. | M | 3 |
| RF-15 | Recorrido | Mapa del recorrido con la ruta ordenada y botón «reproducir viaje». | M | 4 |
| RF-16 | Recorrido | Línea temporal sincronizada con el mapa. | S | 4 |
| RF-17 | Recorrido | Estadísticas del viaje: km, países, lectores, días en libertad. | S | 4 |
| RF-18 | Recorrido | Tarjeta compartible del viaje (imagen para redes). | C | 9 |
| RF-19 | Búsqueda | Búsqueda por título, autor, ZBID, idioma, ciudad. | M | 4 |
| RF-20 | Búsqueda | Libros liberados cerca de la zona de referencia o de mi ubicación. | S | 4 |
| RF-21 | Zonas | Zonas ZBZ: alta por miembros, aprobación por moderadores, libros presentes. | S | 4 |
| RF-22 | Notif. | Notificaciones email + internas (captura de mi libro, invitaciones, menciones). | M | 4 |
| RF-23 | Reseñas | Reseña con valoración 1–5 por obra (una por usuario y obra, longitud mínima). | M | 5 |
| RF-24 | Reseñas | Ficha de obra con valoración media y reseñas de todos sus ejemplares. | S | 5 |
| RF-25 | Admin | Panel Filament: usuarios, libros, zonas, canales, eventos, denuncias, roles. | M | 5 |
| RF-26 | Admin | Denuncias de contenido y moderación. | S | 5 |
| RF-27 | Gamific. | Puntos por acciones (ver sección 6), con límites diarios. | M | 6 |
| RF-28 | Gamific. | Insignias por logros. | M | 6 |
| RF-29 | Gamific. | Niveles según puntos acumulados. | S | 6 |
| RF-30 | Gamific. | Rankings mensuales por zona/ciudad y global. | S | 6 |
| RF-31 | Gamific. | Configuración de puntos e insignias desde el admin. | C | 6 |
| RF-32 | Blog | Blog personal: entradas en Markdown, borrador/publicada, imágenes. Sin me gusta ni comentarios. | M | 7 |
| RF-33 | Canales | Canales privados creados por cualquier miembro, por invitación. | M | 7 |
| RF-34 | Canales | Canales públicos creados solo por moderadores/admin. | M | 7 |
| RF-35 | Canales | Mensajes con Markdown, menciones y edición; aviso de no leídos. | M | 7 |
| RF-36 | Canales | Mensajes directos (canal privado de dos personas). | S | 7 |
| RF-37 | Canales | Futuro: crear canales privados requiere una reputación mínima configurable. | C | — |
| RF-38 | Eventos | Crear evento: título, fecha, lugar o zona ZBZ, descripción, plazas máximas. | M | 8 |
| RF-39 | Eventos | Eventos abiertos (unirse libremente) o solo por invitación. | M | 8 |
| RF-40 | Eventos | Inscripción con control de plazas y lista de espera; exportar a calendario (.ics). | S | 8 |
| RF-41 | Eventos | Sugerir eventos de la zona de referencia del usuario. | S | 8 |
| RF-42 | Import. | Importar CSV de Goodreads: obras, valoraciones/reseñas y «to-read» a la wishlist. | S | 9 |
| RF-43 | Social | Wishlist con aviso si un libro deseado se libera en mi zona. | S | 9 |
| RF-44 | Social | Seguir libros y usuarios; feed de actividad. | C | 9 |
| RF-45 | General | Interfaz en español, inglés y catalán según la preferencia del usuario, con una sola URL por recurso. | M | 1 |
| RF-46 | General | Estadísticas globales. | C | 9 |
| RF-47 | API | API REST /api/v1 con los mismos casos de uso que la web, construida junto a cada funcionalidad. | M | 1–9 |
| RF-48 | API | Login por API con token Sanctum por dispositivo y abilities. | M | 1 |
| RF-49 | API | Ver y revocar dispositivos conectados desde el perfil. | S | 1 |
| RF-50 | API | Documentación OpenAPI 3.1 navegable (Scramble). | M | 1 |
| RF-51 | API | Endpoints geográficos en GeoJSON: recorrido, libros cerca, zonas, eventos. | M | 4 |
| RF-52 | Social | Amigos: solicitar, aceptar y deshacer la amistad. Entre amigos se ve quién liberó o capturó. | S | 9 |
| RF-53 | Eventos | Eventos oficiales creados por moderadores y admins desde el panel. | M | 8 |

# Gamificación

Principio: los puntos importantes llegan cuando otra persona interactúa con tus libros, así se premia la circulación real y se desincentivan las trampas.

**Tabla de puntos (valores iniciales, configurables)**

| Acción | Puntos | Límite |
|---|---|---|
| Registrar un libro | +1 | 10 / día |
| Liberar un libro | +3 | 10 / día |
| Capturar un libro | +2 | Una vez por ejemplar |
| Alguien captura un libro tuyo | +5 | Sin límite |
| Entrada de diario | +1 | Una por ejemplar y poseedor |
| Reseña de una obra | +2 | Una por obra; longitud mínima |
| Asistir a un evento (confirmado por el organizador) | +3 | — |
| Organizar un evento con asistentes | +5 | — |

**Insignias (ejemplos)**

- Primer vuelo: primera liberación. Cazador: 10 capturas. Trotamundos: un libro tuyo en 5 países.
- Maratón: un libro tuyo recorre 1.000 km. Crítico: 10 reseñas. Anfitrión: organizar 3 eventos.
- Fundador de zona: zona ZBZ aprobada. Cronista: 10 entradas de blog.

**Niveles**

Lector → Lector habitual → Liberador → Cazador de libros → Bibliotecario → Bibliotecario global. Se calculan a partir de los puntos acumulados.

**Diseño técnico**

Cada acción dispara un evento de dominio (BookReleased, BookCaught, ReviewPublished…). Un listener en cola registra una transacción de puntos (libro de cuentas inmutable) y evalúa insignias. Los rankings se calculan con una tarea programada y se cachean.

# Comunidad: blog, canales y eventos

**Blog personal**

- Es la página principal del perfil: un espacio propio, no orientado a recibir reacciones.
- Entradas en Markdown con imágenes, borrador o publicada, URL amigable.
- Sin me gusta ni comentarios en esta versión (decisión revisable en el futuro).

**Canales**

| Tipo | Quién lo crea | Quién entra |
|---|---|---|
| Público | Moderadores y administradores (también desde Filament) | Cualquier miembro |
| Privado | Cualquier miembro (en el futuro, con reputación mínima) | Solo invitados |
| Directo | Cualquier miembro | Las dos personas implicadas |

Mensajes con Markdown, menciones (@usuario), edición y contador de no leídos. Tiempo real opcional con Laravel Reverb (websockets); en la primera versión basta con actualización periódica de Livewire.

**Eventos / quedadas**

- Datos: título, descripción, fecha y hora, lugar en mapa o zona ZBZ, plazas máximas.
- Modo abierto (unirse libremente) o por invitación.
- Lista de espera cuando se llenan las plazas; exportación .ics; recordatorio por email.
- Se sugieren a los usuarios de la misma zona de referencia.
- Eventos oficiales: los crean moderadores y admins desde el panel, como motor de captación.

**Zona de referencia del usuario**

- Cada usuario se vincula a una zona (área/ciudad en el mapa, opcionalmente una ZBZ favorita).
- Mudanza: cambio permanente. Viaje: cambio temporal con fecha de fin; al terminar vuelve sola a la habitual.
- Se usa para «libros cerca», sugerencias de eventos, avisos de wishlist y rankings por zona.

**Amigos**

- Amistad aceptada por las dos partes. Se puede deshacer; más adelante, también bloquear.
- Entre amigos se ve quién registró, liberó o capturó un libro, también en el historial anterior. Al deshacer la amistad se vuelve a ocultar.

# Requisitos no funcionales

| ID | Categoría | Requisito |
|---|---|---|
| RNF-01 | Usabilidad | Responsive (móvil primero): capturar un libro desde el móvil en menos de 1 min. |
| RNF-02 | Rendimiento | Páginas principales < 500 ms en servidor; paginación y caché. |
| RNF-03 | Seguridad | Hash de contraseñas, CSRF en web, tokens con hash y caducidad en API, validación en servidor, rate limiting en login, captura y mensajes. |
| RNF-04 | Privacidad | RGPD: aviso legal, política de privacidad y consentimiento desde el registro (v0.2.0); exportar y borrar cuenta (v1.0.0); ubicaciones de usuario aproximadas; autores anónimos y horas en franjas en el ciclo del libro. |
| RNF-05 | Disponibilidad | Entorno reproducible con Docker; copias de seguridad diarias. |
| RNF-06 | Mantenibilidad | Pint, Larastan nivel 6+, PHP Insights ≥ 90 por categoría, cobertura de tests > 70% en dominio. |
| RNF-07 | Escalabilidad | Emails, puntos, geocodificación e importaciones en colas (Redis). |
| RNF-08 | Accesibilidad | WCAG 2.1 AA en formularios y navegación. |
| RNF-09 | Integridad | Puntos como transacciones inmutables; el total se puede recalcular. |
| RNF-10 | API | Versionado en URL, errores RFC 9457, paginación por cursor, rate limiting, tests de contrato por endpoint; siempre en inglés. |
| RNF-11 | Geodatos | Coordenadas en geography(Point, 4326) con índices GiST; consultas de cercanía < 200 ms. |
| RNF-12 | Observabilidad | Logs diarios conservados 7 días y errores enviados a Slack en preproducción y producción. |

# Casos de uso principales

**CU-01 – Registrar un libro**

| | |
|---|---|
| Actor | Miembro |
| Precondición | Sesión iniciada. |
| Flujo principal | 1. Pulsa «Registrar libro» e introduce el ISBN. 2. El sistema autocompleta los datos de la obra. 3. Indica estado y una nota; el sistema genera el ZBID y la entrada de diario. 4. Descarga la etiqueta PDF. 5. Recibe +1 punto. |
| Alternativas | 2a. ISBN no encontrado: datos manuales. |

**CU-02 – Liberar un libro**

| | |
|---|---|
| Actor | Poseedor actual |
| Precondición | El libro está en su estantería. |
| Flujo principal | 1. Pulsa «Liberar» y elige tipo (wild / controlada / ZBZ). 2. Marca la ubicación y añade pistas. 3. El libro pasa a «liberado» y aparece en el mapa al momento; se crea la entrada de diario; +3 puntos. 4. Los usuarios de la zona con esa obra en su wishlist reciben aviso. |
| Alternativas | 1a. Controlada: indica el destinatario, que recibe una notificación. |

**CU-03 – Capturar un libro**

| | |
|---|---|
| Actor | Visitante o Miembro |
| Precondición | Tiene el ZBID o el QR. |
| Flujo principal | 1. Introduce el ZBID o escanea el QR y ve ficha y diario. 2. Pulsa «Lo he encontrado», indica dónde y comenta. 3. Se crea la entrada; se notifica al registrador y a los lectores anteriores, que ven «un usuario» o «un invitado» salvo que sean amigos. 4. Miembro: el libro pasa a su estantería; +2 puntos para él y +5 para el registrador. |
| Alternativas | 1a. ZBID inexistente: error y límite de intentos. 2a. Visitante: entrada anónima. |

**CU-04 – Ver el recorrido de un libro**

| | |
|---|---|
| Actor | Cualquiera |
| Precondición | El libro tiene al menos una liberación. |
| Flujo principal | 1. Abre la ficha del ejemplar y la pestaña «Viaje». 2. Ve el mapa con la ruta y la línea temporal, con fechas y franjas. 3. Pulsa «Reproducir viaje»: la ruta se dibuja en orden y la línea temporal avanza sincronizada. 4. Consulta km, países, lectores y días en libertad. |
| Alternativas | — |

**CU-05 – Crear un evento**

| | |
|---|---|
| Actor | Miembro (o moderador/admin desde el panel, para eventos oficiales) |
| Precondición | Sesión iniciada. |
| Flujo principal | 1. Pulsa «Nuevo evento» y rellena título, fecha, lugar o zona y plazas. 2. Elige abierto o por invitación. 3. Si es por invitación, selecciona a los invitados, que reciben aviso. 4. Los asistentes se inscriben hasta completar plazas; después, lista de espera. |
| Alternativas | 4a. Si alguien se da de baja, entra el primero de la lista de espera. |

**CU-06 – Cambiar de zona por un viaje**

| | |
|---|---|
| Actor | Miembro |
| Precondición | Tiene zona habitual. |
| Flujo principal | 1. En su perfil pulsa «Estoy de viaje». 2. Elige la zona temporal y la fecha de vuelta. 3. Ve libros cerca y eventos de la zona temporal. 4. En la fecha de fin, el sistema restaura la zona habitual. |
| Alternativas | 1a. Mudanza: cambia la zona habitual de forma permanente. |

# Modelo de datos

Entidades principales (PostgreSQL + PostGIS). Las coordenadas son columnas location geography(Point, 4326). Se omiten timestamps y soft deletes. El diagrama ER completo está en `docs/diagramas/` (sección 13).

| Entidad | Campos clave | Relaciones |
|---|---|---|
| users | username, email, password, avatar, bio, locale, points_total, level, privacy_accepted_at | 1:N books, posts, user_zones… |
| user_zones | user_id, label, location, radius_km, zone_id?, type (home/temporary), ends_at | N:1 users, N:1 zones |
| friendships | requester_id, addressee_id, status (pending/accepted), accepted_at | N:M users–users |
| works | isbn, title, author, language, genre, cover_url | 1:N books, 1:N reviews |
| books | zbid (único), work_id, registered_by, current_holder_id, status, condition | 1:N releases, journal_entries |
| releases | book_id, user_id, type, zone_id?, location, place_text, released_at, hint, recipient_id? | N:1 books |
| journal_entries | book_id, user_id?, type, body, location, city, is_anonymous | N:1 books |
| reviews | work_id, user_id, rating, body (único: work_id+user_id) | N:1 works, users |
| zones | name, address, city, location, hours, approved, created_by | 1:N releases, events |
| point_transactions | user_id, action, points, subject_type/id, created_at | N:1 users (inmutable) |
| badges / user_badges | code, name, icon, rule · user_id, badge_id, awarded_at | N:M users–badges |
| posts | user_id, title, slug, body_md, status, published_at | N:1 users |
| channels | name, slug, type (public/private/direct), created_by | 1:N channel_members, messages |
| channel_members | channel_id, user_id, role, last_read_at | N:M users–channels |
| messages | channel_id, user_id, body_md, edited_at | N:1 channels |
| events | organizer_id, title, starts_at, zone_id?, location, capacity, visibility (open/invite), is_official | 1:N event_attendees |
| event_attendees | event_id, user_id, status (invited/going/waitlist/declined/attended) | N:M users–events |
| wishlists / follows | user_id, work_id · follower_id, followable (polimórfica) | — |
| imports | user_id, source (goodreads), file, status, stats | N:1 users |
| reports | reporter_id, reportable (polimórfica), reason, status | — |
| personal_access_tokens | tokenable, name (dispositivo), token (hash), abilities, expires_at | N:1 users (Sanctum) |

Estados del ejemplar: en estantería → liberado → capturado → (liberado de nuevo…) / archivado / perdido.

Las horas (`released_at` y similares) se guardan exactas en UTC; la web y la API solo exponen la fecha y la franja (ADR-0010).

# Stack tecnológico

| Capa | Tecnología | Motivo |
|---|---|---|
| Backend | Laravel 13 (PHP 8.4) | Framework base. ADR-0009. |
| Base de datos | PostgreSQL 16 + PostGIS 3 | Geodatos de primera clase: cercanía, rutas, distancias, GeoJSON. ADR-0006. |
| Geo en Laravel | clickbar/laravel-magellan | Tipos Point/LineString y funciones PostGIS en Eloquent. |
| Frontend | Livewire 4 + Alpine.js + Tailwind CSS 4 | Interactividad sin API ni SPA. |
| Admin | Filament | Mismo stack (Tailwind/Livewire); CRUDs rápidos. ADR-0002. |
| Contenedores | Docker Compose | Entorno idéntico en local y producción. |
| Auth / permisos | Kit de autenticación de Laravel (por decidir en la v0.2.0) + Sanctum (tokens API) + spatie/laravel-permission | Una cuenta, dos accesos. ADR-0008. |
| API | API Resources + spatie/laravel-query-builder | Respuestas consistentes, filtros y orden. ADR-0007. |
| Docs API | Scramble (OpenAPI 3.1) | Documentación generada desde el código. |
| Colas / caché | Redis + Laravel Horizon | Puntos, emails, importaciones en segundo plano. |
| Tiempo real | Laravel Reverb (opcional) | Canales y notificaciones en vivo. |
| Búsqueda | Full-text de PostgreSQL (tsvector + unaccent) | Sin servicio extra; Meilisearch como opción futura. |
| Mapas | Leaflet + OpenStreetMap (Nominatim) | Recorrido animado y libros cerca; sin coste en desarrollo. Proveedor de producción por decidir antes de la v1.0.0. |
| Datos de libros | Open Library API (+ Google Books) | Autocompletado por ISBN. |
| PDF / QR | laravel-dompdf + chillerlan/php-qrcode | Etiquetas imprimibles; QR en SVG. |
| Markdown | league/commonmark | Blog y mensajes. |
| Idiomas | Traducciones JSON de Laravel (es, en, ca) | Código en inglés; web en el idioma del usuario. ADR-0011. |
| Calidad / CI | Pest, Larastan, Pint, PHP Insights, GitHub Actions | Tests, análisis estático, estilo y puntuación del código. |
| Diagramas | Mermaid (en el repositorio) | Diagramas como código, versionados. |

# Arquitectura y entorno Docker

| Servicio | Imagen | Función |
|---|---|---|
| app | php:8.4-fpm | Aplicación Laravel |
| web | nginx:alpine | Servidor web |
| db | postgis/postgis:16-3.4 | PostgreSQL + PostGIS (bases `zbooks` y `zbooks_testing`) |
| redis | redis:alpine | Colas, caché, sesiones |
| queue | imagen app | Horizon (workers) |
| scheduler | imagen app | Tareas programadas (rankings, zonas temporales) |
| mailpit | axllent/mailpit | Emails en desarrollo |
| reverb | imagen app | Websockets (opcional) |
| node | node:22 (dev) | Vite |

**Organización del código**

- Dos entradas, un dominio: Livewire (web) y controladores /api/v1 (API) llaman a las mismas Actions.
- Actions: RegisterBook, ReleaseBook, CatchBook, AwardPoints, ImportGoodreads…
- Events/Listeners: BookCaught → NotifyReaders + AwardPoints + CheckBadges.
- Policies para autorización (quién libera, quién crea canales públicos, quién ve al autor de una entrada…).
- Livewire por pantalla; Filament para /admin.
- Enums PHP: BookStatus, ReleaseType, ChannelType, EventVisibility, AttendeeStatus.
- Clases, rutas y textos fuente en inglés; tests, comentarios y documentación en español.

# Diagramas (fase de diseño)

Se elaboran en Mermaid dentro de docs/diagramas/ antes de programar, y se actualizan cuando el diseño cambie. Al terminar el MVP se generará el ER desde las migraciones para comparar diseño y realidad.

| Diagrama | Tipo | Para qué sirve |
|---|---|---|
| Casos de uso | UML | Visión de qué puede hacer cada actor. |
| Modelo de dominio / clases | UML | Entidades, atributos y relaciones a nivel de código. |
| Estados del ejemplar | UML (máquina de estados) | Transiciones válidas del libro. |
| Secuencia: capturar un libro | UML | Interacción entre controlador, acciones, eventos y colas. |
| Secuencia: otorgar puntos | UML | Flujo de gamificación de evento a insignia. |
| Entidad-relación | ER | Tablas, claves e índices de PostgreSQL + PostGIS. |
| Despliegue | UML | Contenedores Docker y su comunicación. |
| Arquitectura web + API | Capas | Cómo web y API comparten el dominio; convenciones de la API. |
| Secuencia: autenticación | UML | Registro, verificación de email, sesión web y tokens. |

# Proceso de trabajo

Equipo: el autor (product owner y desarrollador) y Claude (desarrollador y arquitecto).

| Práctica | Cómo se aplica |
|---|---|
| Historias de usuario | «Como [rol] quiero [acción] para [beneficio]» + criterios de aceptación. |
| Issues + tablero | Una issue por historia en GitHub Projects: Backlog → Todo → In Progress → In Review → Done. Épicas para el trabajo futuro, refinadas antes de cada iteración. |
| Ramas y PR | master siempre estable y protegida: solo se fusiona mediante pull request revisado y con la CI en verde. Una rama por issue (feature/…, fix/…, docs/…, chore/…). |
| Commits convencionales | feat:, fix:, docs:, refactor:, test:, chore: |
| SemVer + CHANGELOG | Una versión menor por iteración (0.1.0 … 0.5.0 = MVP … 1.0.0), con etiqueta y release en GitHub. |
| ADRs | docs/adr/NNNN-titulo.md: contexto, decisión, consecuencias. |
| Diario de desarrollo | docs/DIARIO.md: una entrada por sesión. |
| Retrospectiva | Al cerrar cada iteración: qué fue bien, qué fue mal, qué mejoramos. |
| Análisis funcional | Versión nueva de este documento al cerrar una iteración con cambios relevantes. Entre versiones, los cambios se acumulan en `docs/analisis/cambios-pendientes.md`. |
| Entornos | Local (Docker), preproducción y producción. La preproducción usa copias anonimizadas de producción. |

**ADRs**

| ADR | Decisión |
|---|---|
| 0001 | Stack: Laravel + Livewire + Docker (sin mezclar Vue) |
| 0002 | Panel de administración con Filament en lugar de AdminLTE |
| 0003 | Diagramas como código con Mermaid |
| 0004 | Puntos como transacciones inmutables |
| 0005 | Mensajes directos como canales privados de dos miembros |
| 0006 | PostgreSQL + PostGIS en lugar de MySQL |
| 0007 | API REST versionada construida junto a cada funcionalidad |
| 0008 | Autenticación: sesión en web, tokens Sanctum en API, verificación de email |
| 0009 | Laravel 13 y PHP 8.4 |
| 0010 | Privacidad en el ciclo del libro: anonimato por defecto y horas en franjas |
| 0011 | Idiomas: código y API en inglés, web en el idioma de cada usuario |

# Planificación por iteraciones

Iteraciones de 1–2 semanas a tiempo parcial (~10–15 h/semana). Cada una cierra con entregable, versión etiquetada, retrospectiva y entrada en el diario.

**Fase D – Diseño** · 1 semana · ✅ completada

- Diagramas UML y ER en Mermaid (incluye arquitectura web + API).
- ADRs 0001–0008.
- Backlog de historias en GitHub Projects.

Entregable: Diseño aprobado y backlog listo.

**Iteración 0 – Entorno** · 1 semana · v0.1.0 · ✅ publicada el 01/11/2026 (2 días)

- Repositorio, convenciones, docker-compose con PostGIS.
- Laravel + Tailwind + Vite, layout base.
- Esqueleto /api/v1 en inglés, formato de errores RFC 9457, Scramble.
- Pest, Pint, Larastan, PHP Insights y GitHub Actions.
- README con instalación en un comando (`make setup`).

Entregable: Proyecto arranca con un comando; CI en verde; /docs/api visible.

**Iteración 1 – Usuarios, autenticación y zona** · 2 semanas · v0.2.0

- Kit de autenticación (web) con verificación de email obligatoria.
- API: registro, token por dispositivo, logout, /me; gestión de dispositivos.
- Perfil público y zona de referencia (geography) habitual/temporal.
- Web en español, inglés y catalán.
- Aviso legal, política de privacidad y consentimiento en el registro.
- Tests de contrato de los endpoints de auth.

Entregable: Cuentas verificadas, web y API funcionando con la misma cuenta.

**Iteración 2 – Libros, ZBID y etiquetas** · 2 semanas · v0.3.0

- Obras y ejemplares; ISBN con Open Library.
- Generador de ZBID con tests.
- Estantería, etiqueta PDF con QR y QR en la ficha (`/c/{ZBID}`).
- API: /books, /me/shelf, /works/lookup?isbn=

Entregable: Registrar libros y descargar etiquetas (web y API).

**Iteración 3 – Liberar, capturar y diario** · 2 semanas · v0.4.0

- Liberación con mapa (location geography).
- Captura por ZBID/QR, anónima o con cuenta.
- Diario y policies; autores anónimos y horas en franjas.
- API: /releases, /catches, /journal.

Entregable: Ciclo completo del libro.

**Iteración 4 – Recorrido, búsqueda, zonas, notificaciones** · 3 semanas · v0.5.0 (MVP)

- Recorrido con ST_MakeLine, km con ST_Length, «reproducir viaje» y línea temporal.
- Libros cerca con ST_DWithin; búsqueda full-text.
- Zonas ZBZ.
- Notificaciones email e internas.
- Datos de demostración en Barcelona.
- API GeoJSON: /books/{zbid}/journey, /releases/nearby, /zones.

Entregable: MVP público con API documentada.

**Iteración 5 – Admin y reseñas** · 2 semanas · v0.6.0

- Filament: recursos, roles y denuncias.
- Reseñas y valoración media por obra.
- API: /works/{id}/reviews, /reports.

Entregable: Plataforma moderable y con reseñas.

**Iteración 6 – Gamificación** · 2 semanas · v0.7.0

- Transacciones de puntos y límites.
- Insignias, niveles y rankings por zona.
- Configuración en Filament.
- API: /me/points, /me/badges, /rankings.

Entregable: Sistema de puntos e insignias visible en perfiles.

**Iteración 7 – Blog y canales** · 2 semanas · v0.8.0

- Blog personal en Markdown como portada del perfil.
- Canales públicos, privados y directos; menciones y no leídos.
- API: /users/{u}/posts, /channels, /channels/{id}/messages.

Entregable: Espacio personal y conversación.

**Iteración 8 – Eventos** · 2 semanas · v0.9.0

- Eventos abiertos/por invitación, plazas y lista de espera.
- Eventos oficiales desde el panel.
- Vinculación a zona, .ics y recordatorios.
- API: /events (GeoJSON), /events/{id}/attendees.

Entregable: Quedadas de liberación.

**Iteración 9 – Importación y social** · 2 semanas · v0.10.0

- Importación Goodreads en cola.
- Wishlist con avisos, seguir, feed.
- Amigos: solicitar, aceptar y deshacer.
- Estadísticas.
- API: /imports, /me/wishlist, /feed, /me/friends.

Entregable: Producto completo.

**Iteración 10 – Producción** · 1 semana · v1.0.0

- Rendimiento (EXPLAIN, índices GiST/GIN), accesibilidad, SEO.
- RGPD: exportar y anonimizar la cuenta.
- Preproducción y despliegue en VPS con HTTPS, backups y monitorización.
- Observabilidad: logs diarios y errores a Slack.

Entregable: Versión 1.0 en producción.

# Calendario resumen

| Iteración | Semanas | Versión |
|---|---|---|
| Fase D – Diseño | 1 | — |
| It. 0 – Entorno | 2 | 0.1.0 |
| It. 1 – Usuarios/Auth | 3–4 | 0.2.0 |
| It. 2 – Libros/ZBID | 5–6 | 0.3.0 |
| It. 3 – Ciclo | 7–8 | 0.4.0 |
| It. 4 – Recorrido/MVP | 9–11 | 0.5.0 |
| It. 5 – Admin/Reseñas | 12–13 | 0.6.0 |
| It. 6 – Gamificación | 14–15 | 0.7.0 |
| It. 7 – Blog/Canales | 16–17 | 0.8.0 |
| It. 8 – Eventos | 18–19 | 0.9.0 |
| It. 9 – Import./Social | 20–21 | 0.10.0 |
| It. 10 – Producción | 22 | 1.0.0 |

Duración total estimada: ~22 semanas (≈ 5 meses a tiempo parcial). La fase D y la iteración 0 han durado menos de lo previsto; el calendario no se recorta hasta ver cómo va la iteración 1, la primera con funcionalidades.

| Hito | Semana | Versión | Contenido |
|---|---|---|---|
| H0 – Diseño | 1 | — | Diagramas, ADRs, backlog |
| H1 – Base técnica | 4 | 0.2.0 | Entorno, autenticación web + API, zona, idiomas |
| H2 – Ciclo del libro | 8 | 0.4.0 | Registrar, liberar, capturar, diario |
| H3 – MVP público | 11 | 0.5.0 | Recorrido PostGIS, búsqueda, zonas, notificaciones, API documentada |
| H4 – Comunidad | 19 | 0.9.0 | Admin, reseñas, gamificación, blog, canales, eventos |
| H5 – v1.0 | 22 | 1.0.0 | Importación, social, producción |

# Definición de terminado (DoD)

- Cumple los criterios de aceptación de la historia, en web y en API.
- Endpoint documentado en OpenAPI y con tests de contrato.
- Tests de feature (Pest) para el flujo principal y los errores.
- Pint, Larastan, PHP Insights y CI en verde.
- Funciona en móvil y escritorio; textos traducibles.
- Fusionado mediante pull request revisado.
- Diagramas, ADR, CHANGELOG y diario actualizados si aplica.

# Riesgos y mitigaciones

| Riesgo | Impacto | Mitigación |
|---|---|---|
| Alcance que crece | Alto | MoSCoW, backlog futuro y nada post-MVP antes de la 0.5.0. |
| La iteración 1 es la más grande (auth, tokens, roles, perfil, zonas, idiomas, RGPD) | Alto | Refinamiento antes de empezar; mover a la iteración 2 lo que no sea imprescindible. |
| Trampas en la gamificación | Medio | Puntos ligados a acciones de terceros, límites diarios, transacciones auditables. |
| Abuso de captura de ZBID | Medio | Rate limiting, ZBID no predecible. |
| Spam en canales y blog | Medio | Verificación de email, rate limiting, denuncias, futura reputación mínima. |
| Privacidad de ubicaciones y rutinas | Alto | Zonas de usuario aproximadas; autores anónimos salvo entre amigos; horas en franjas (ADR-0010). |
| Curva de aprendizaje de PostGIS | Medio | Encapsular consultas en scopes/servicios; tests con datos geográficos reales. |
| La API duplica el trabajo de cada funcionalidad | Medio | Lógica solo en Actions; controladores y componentes finos. |
| Formato del CSV de Goodreads | Bajo | Importación tolerante, informe de errores, en cola. |
| Límites de APIs externas | Medio | Caché, colas y entrada manual. |
| Uso de la marca «BookCrossing» | Medio | Nombre propio del proyecto: zBooks. |

# Backlog futuro

- Reputación mínima para crear canales privados (RF-37).
- Bloquear usuarios.
- Me gusta y comentarios en el blog (a estudiar).
- Book rings / book rays.
- App móvil que consuma la API v1.
- PWA con escáner QR por cámara.
- Retos de lectura y listas curadas.
- Resumen semanal por email.
- Meilisearch si el full-text de PostgreSQL se queda corto.
- Difuminar la ubicación de liberación en zonas sensibles (cerca de casa).
- Estimación por puntos de historia.
- Seguimiento de errores (Sentry o Flare), monitor de disponibilidad y métricas.
