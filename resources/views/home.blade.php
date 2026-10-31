<x-layouts::app>
    {{-- Portada: qué es zBooks y la etiqueta del libro como protagonista --}}
    <section class="mx-auto grid max-w-6xl items-center gap-12 px-4 pb-16 pt-12 sm:px-6 lg:grid-cols-12 lg:pt-20">
        <div class="lg:col-span-7">
            <h1 class="font-display text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">
                Deja un libro donde alguien lo encuentre. Mira hasta dónde llega.
            </h1>
            <p class="mt-6 max-w-xl text-lg text-tinta-suave">
                Registras el libro, le pegas una etiqueta con su código y lo dejas en un banco, un tren o una cafetería.
                Cada persona que lo encuentra cuenta dónde estaba, y tú ves su viaje en el mapa.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-4 font-display">
                <span class="cursor-not-allowed rounded-md bg-sello/50 px-5 py-3 font-semibold text-white"
                      title="Disponible en la versión 0.3.0">
                    Registrar mi primer libro
                </span>
                <a href="#como-funciona" class="font-semibold text-sello underline decoration-2 underline-offset-4 hover:text-sello-oscuro">
                    Ver cómo funciona
                </a>
            </div>
        </div>

        {{-- La etiqueta: el mismo objeto que lleva pegado cada libro --}}
        <div class="lg:col-span-5">
            <div class="etiqueta mx-auto max-w-md -rotate-2 rounded-b-lg px-7 pb-8 pt-9 shadow-[0_18px_40px_-24px_rgba(23,32,43,0.55)]">
                <div class="flex items-center justify-between">
                    <p class="font-display text-sm font-semibold text-tinta/80">zBooks</p>
                    <x-logo class="h-9 w-9" />
                </div>
                <p class="mt-4 font-display text-2xl font-bold leading-tight">Este libro está de viaje.</p>
                <p class="mt-2 text-[0.95rem] leading-snug text-tinta/85">
                    ¿Lo has encontrado? Escribe el código de su etiqueta para ver de dónde viene y contar dónde estaba.
                </p>

                <form action="{{ route('zbid.buscar') }}" method="GET" class="mt-6">
                    <label for="zbid-portada" class="block font-display text-sm font-semibold">Código del libro</label>
                    <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                        <input id="zbid-portada" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                               placeholder="ZB-4K7Q-92XM"
                               class="min-w-0 flex-1 rounded-md border-2 border-tinta/80 bg-white/90 px-3 py-2.5 font-mono text-lg uppercase tracking-wider placeholder:text-tinta/35 focus:border-sello focus:outline-none">
                        <button type="submit"
                                class="rounded-md bg-sello px-5 py-2.5 font-semibold text-white hover:bg-sello-oscuro">
                            Buscar libro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Cómo funciona: es una secuencia real, por eso va numerada y unida por una ruta --}}
    <section id="como-funciona" class="border-t border-linea bg-papel-hondo/60">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h2 class="font-display text-3xl font-bold tracking-tight">Cómo funciona</h2>

            <ol class="relative mt-12 grid gap-12 md:grid-cols-3 md:gap-8">
                {{-- Línea de la ruta que une los pasos (solo en escritorio) --}}
                <svg class="pointer-events-none absolute left-0 top-6 hidden h-4 w-full md:block" preserveAspectRatio="none" viewBox="0 0 100 4" aria-hidden="true">
                    <line x1="2" y1="2" x2="69" y2="2" stroke="#1e7a6e" stroke-width="0.6" stroke-dasharray="1.6 1.4" vector-effect="non-scaling-stroke" style="stroke-width:2px"/>
                </svg>

                @foreach ([
                    ['Registra el libro', 'Busca el libro por su ISBN y zBooks le da un código único. Imprime la etiqueta y pégala dentro de la cubierta.'],
                    ['Déjalo en libertad', 'Elige un sitio visible: un banco, una estación, una biblioteca de barrio. Márcalo en el mapa para que otros lo busquen.'],
                    ['Alguien lo encuentra', 'Quien lo recoge escribe el código, cuenta dónde lo encontró y, cuando lo termine, vuelve a dejarlo libre.'],
                ] as $i => [$titulo, $texto])
                    <li class="relative">
                        <span class="relative z-10 flex h-12 w-12 items-center justify-center rounded-full border-2 border-ruta bg-papel font-display text-lg font-bold text-ruta">
                            {{ $i + 1 }}
                        </span>
                        <h3 class="mt-5 font-display text-xl font-bold">{{ $titulo }}</h3>
                        <p class="mt-2 max-w-sm text-tinta-suave">{{ $texto }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Estado del proyecto, dicho con honestidad --}}
    <section class="mx-auto max-w-6xl px-4 pt-16 sm:px-6">
        <div class="max-w-2xl border-l-4 border-sello pl-6">
            <h2 class="font-display text-xl font-bold">zBooks está en construcción</h2>
            <p class="mt-2 text-tinta-suave">
                Es un proyecto personal que se construye por versiones. Los botones en gris se activarán a medida que lleguen sus funciones.
                Puedes seguir el desarrollo en <a href="https://github.com/zStrikke/zBooks" class="font-semibold text-sello underline underline-offset-4">GitHub</a>.
            </p>
        </div>
    </section>
</x-layouts::app>
