@php($titular = config('legal.controller'))
<x-layouts::app title="Aviso legal" description="Quién está detrás de zBooks y condiciones de uso.">
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
        <header class="border-b border-linea pb-8">
            <h1 class="font-display text-4xl font-extrabold tracking-tight">Aviso legal</h1>
            <p class="mt-3 text-tinta-suave">Quién está detrás de zBooks y qué condiciones aceptas al usarlo.</p>
        </header>

        <div class="mt-10 space-y-10 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_li]:mt-2 [&_p]:mt-3 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:pl-6">
            <section>
                <h2>1. Titular</h2>
                <ul>
                    <li>Titular: <strong>{{ $titular['name'] }}</strong></li>
                    <li>País: {{ $titular['country'] }}</li>
                    <li>Contacto: <strong>{{ $titular['email'] }}</strong></li>
                </ul>
                <p>
                    zBooks es un proyecto personal, no comercial y de aprendizaje. No vende nada, no cobra por su uso y no muestra publicidad.
                    El código fuente es público en <a href="https://github.com/zStrikke/zBooks" class="text-sello underline underline-offset-4">GitHub</a>.
                </p>
            </section>

            <section>
                <h2>2. Qué es zBooks</h2>
                <p>
                    Una plataforma para registrar libros, dejarlos en lugares públicos para que otras personas los encuentren
                    y seguir su viaje. Está inspirada en BookCrossing, pero no tiene relación con esa marca.
                </p>
            </section>

            <section>
                <h2>3. Condiciones de uso</h2>
                <ul>
                    <li>Escribe con respeto. No publiques contenido ofensivo, ilegal, publicidad ni datos personales de otras personas.</li>
                    <li>
                        Deja los libros en sitios donde esté permitido y no estorben. Quien libera un libro es responsable de dónde lo deja
                        y de respetar las normas del lugar.
                    </li>
                    <li>No uses zBooks para fines distintos de los suyos ni intentes acceder a datos o cuentas de otras personas.</li>
                    <li>Podemos ocultar contenido o suspender cuentas que incumplan estas condiciones.</li>
                </ul>
            </section>

            <section>
                <h2>4. Contenido de los usuarios</h2>
                <p>
                    Lo que escribes (diario, reseñas, blog) es tuyo y eres responsable de ello. Al publicarlo, nos permites mostrarlo
                    en zBooks mientras siga publicado. Las entradas del diario de un libro se conservan aunque borres tu cuenta,
                    sin tu nombre, para no romper su viaje.
                </p>
            </section>

            <section>
                <h2>5. Responsabilidad</h2>
                <p>
                    Hacemos lo posible para que zBooks funcione bien, pero no podemos garantizar que esté siempre disponible ni libre de errores.
                    No somos responsables de los libros que se dejan en lugares públicos ni de lo que hagan con ellos terceras personas.
                </p>
            </section>

            <section>
                <h2>6. Datos personales</h2>
                <p>
                    Cómo tratamos tus datos está en la <a href="{{ route('legal.privacy') }}" class="text-sello underline underline-offset-4">política de privacidad</a>.
                </p>
            </section>

            <section>
                <h2>7. Legislación aplicable</h2>
                <p>Estas condiciones se rigen por la legislación de {{ $titular['country'] }}.</p>
            </section>
        </div>
    </article>
</x-layouts::app>
