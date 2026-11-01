@php($responsable = config('legal.controller'))
<x-layouts::app title="Política de privacidad" description="Qué datos guarda zBooks, para qué y qué derechos tienes.">
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <header class="border-b border-linea pb-8">
            <h1 class="font-display text-4xl font-extrabold tracking-tight">Política de privacidad</h1>
            <p class="mt-3 text-tinta-suave">
                Versión {{ \App\Legal\PrivacyPolicy::currentVersion() }}. Resumen: guardamos lo mínimo para que los libros viajen,
                no vendemos datos y en público nunca se ve quién deja o encuentra un libro, salvo para sus amigos.
            </p>
        </header>

        <div class="mt-10 space-y-10 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_li]:mt-2 [&_p]:mt-3 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:pl-6">
            <section>
                <h2>1. Quién es el responsable</h2>
                <p>
                    zBooks es un proyecto personal y sin ánimo de lucro. El responsable de tus datos es
                    <strong>{{ $responsable['name'] }}</strong> ({{ $responsable['country'] }}).
                    Para cualquier asunto de privacidad, escribe a <strong>{{ $responsable['email'] }}</strong>.
                </p>
            </section>

            <section>
                <h2>2. Qué datos guardamos</h2>
                <ul>
                    <li><strong>Tu cuenta:</strong> nombre de usuario, email y contraseña (cifrada: nadie puede leerla).</li>
                    <li><strong>Tu perfil:</strong> avatar, biografía e idioma, si los añades.</li>
                    <li>
                        <strong>Tu zona:</strong> el centro de tu zona redondeado a unos 500 metros, o el barrio o la ciudad que elijas.
                        Nunca guardamos tu dirección exacta.
                    </li>
                    <li>
                        <strong>La actividad de tus libros:</strong> los libros que registras, dónde y cuándo los liberas o los capturas,
                        y lo que escribes en su diario.
                    </li>
                    <li>
                        <strong>Datos técnicos:</strong> la dirección IP y el navegador en los registros de seguridad,
                        y las cookies técnicas descritas en el punto 8.
                    </li>
                </ul>
            </section>

            <section>
                <h2>3. Para qué los usamos</h2>
                <ul>
                    <li>Para darte el servicio: gestionar tu cuenta, mostrar el viaje de los libros y los libros cerca de ti.</li>
                    <li>Para la seguridad: evitar abusos, limitar intentos de acceso y detectar errores.</li>
                    <li>Para escribirte sobre el servicio: verificar tu email, recuperar tu contraseña y avisarte de lo que pasa con tus libros.</li>
                </ul>
                <p>No usamos tus datos para publicidad, no hacemos perfiles comerciales y no los vendemos.</p>
            </section>

            <section>
                <h2>4. Por qué podemos usarlos (base legal)</h2>
                <ul>
                    <li><strong>Para prestarte el servicio</strong> que pides al crear tu cuenta (art. 6.1.b del RGPD).</li>
                    <li><strong>Interés legítimo</strong> en mantener el servicio seguro (art. 6.1.f).</li>
                    <li><strong>Tu consentimiento</strong> para lo opcional, como usar la geolocalización de tu navegador (art. 6.1.a). Puedes retirarlo cuando quieras.</li>
                </ul>
            </section>

            <section>
                <h2>5. Qué es público y qué no</h2>
                <ul>
                    <li>
                        <strong>Público:</strong> el lugar donde se libera o se captura un libro, la fecha y la franja del día
                        (madrugada, mañana, tarde o noche), nunca la hora exacta.
                    </li>
                    <li>
                        <strong>Anónimo:</strong> quién registró, liberó o capturó un libro aparece como «un usuario» o «un invitado».
                        Solo tus amigos ven tu nombre.
                    </li>
                    <li><strong>Tu perfil público:</strong> nombre de usuario, avatar, biografía y cifras de actividad, nunca qué libros.</li>
                    <li><strong>Nunca público:</strong> tu email y tu zona.</li>
                </ul>
            </section>

            <section>
                <h2>6. Cuánto tiempo los guardamos</h2>
                <ul>
                    <li>Los datos de tu cuenta, mientras la mantengas.</li>
                    <li>
                        Si borras tu cuenta, eliminamos tus datos personales. El diario de los libros se conserva para no romper su viaje,
                        pero tus entradas pasan a ser de «un usuario».
                    </li>
                    <li>Los registros técnicos de seguridad, un máximo de 7 días.</li>
                </ul>
            </section>

            <section>
                <h2>7. Con quién los compartimos</h2>
                <p>Solo con los servicios necesarios para que zBooks funcione, que tratan los datos por encargo nuestro:</p>
                <ul>
                    <li>Alojamiento del servidor: [proveedor de alojamiento].</li>
                    <li>Envío de emails: [proveedor de email].</li>
                    <li>
                        Mapas: tu navegador descarga los mapas de los servidores de OpenStreetMap, que ven tu dirección IP.
                        Las búsquedas de direcciones pasan por nuestro servidor.
                    </li>
                </ul>
                <p>Transferencias fuera del Espacio Económico Europeo: [completar según los proveedores elegidos].</p>
            </section>

            <section>
                <h2>8. Cookies</h2>
                <p>
                    Solo usamos cookies técnicas, imprescindibles para que la web funcione: la de tu sesión, la de seguridad de los formularios
                    y la de tu idioma. No usamos cookies de análisis ni de publicidad, por eso no te pedimos permiso para ellas.
                </p>
            </section>

            <section>
                <h2>9. Tus derechos</h2>
                <p>
                    Puedes pedir acceder a tus datos, corregirlos, borrarlos, limitarlos, oponerte a su uso o recibirlos en un formato
                    que puedas llevarte a otro servicio. Escribe a <strong>{{ $responsable['email'] }}</strong>.
                </p>
                <p>
                    Si crees que no los tratamos bien, puedes reclamar ante la autoridad de protección de datos de tu país
                    (en España, la <a href="https://www.aepd.es" class="text-sello underline underline-offset-4">Agencia Española de Protección de Datos</a>).
                </p>
            </section>

            <section>
                <h2>10. Edad mínima</h2>
                <p>Para crear una cuenta necesitas tener al menos 14 años.</p>
            </section>

            <section>
                <h2>11. Cambios en esta política</h2>
                <p>
                    Si la cambiamos de forma relevante, publicaremos la nueva versión aquí y te pediremos que la aceptes de nuevo
                    la próxima vez que entres.
                </p>
            </section>
        </div>
    </article>
</x-layouts::app>
