<x-layouts::app>
    {{-- Portada: qué es zBooks y la etiqueta del libro como protagonista --}}
    <section class="mx-auto grid max-w-6xl items-center gap-12 px-4 pb-16 pt-12 sm:px-6 lg:grid-cols-12 lg:pt-20">
        <div class="lg:col-span-7">
            <h1 class="font-display text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl lg:text-6xl">
                {{ __('Leave a book where someone will find it. See how far it goes.') }}
            </h1>
            <p class="mt-6 max-w-xl text-lg text-tinta-suave">
                {{ __('You register the book, stick a label with its code on it and leave it on a bench, a train or a café. Everyone who finds it tells where it was, and you see its journey on the map.') }}
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-4 font-display">
                <span class="cursor-not-allowed rounded-md bg-sello/50 px-5 py-3 font-semibold text-white"
                      title="{{ __('Available in version :version', ['version' => '0.3.0']) }}">
                    {{ __('Register my first book') }}
                </span>
                <a href="#como-funciona" class="font-semibold text-sello underline decoration-2 underline-offset-4 hover:text-sello-oscuro">
                    {{ __('See how it works') }}
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
                <p class="mt-4 font-display text-2xl font-bold leading-tight">{{ __('This book is travelling.') }}</p>
                <p class="mt-2 text-[0.95rem] leading-snug text-tinta/85">
                    {{ __('Did you find it? Type the code on its label to see where it comes from and tell where it was.') }}
                </p>

                <form action="{{ route('zbid.buscar') }}" method="GET" class="mt-6">
                    <label for="zbid-portada" class="block font-display text-sm font-semibold">{{ __('Book code') }}</label>
                    <div class="mt-2 flex flex-col gap-3 sm:flex-row">
                        <input id="zbid-portada" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                               placeholder="ZB-4K7Q-92XM"
                               class="min-w-0 flex-1 rounded-md border-2 border-tinta/80 bg-white/90 px-3 py-2.5 font-mono text-lg uppercase tracking-wider placeholder:text-tinta/35 focus:border-sello focus:outline-none">
                        <button type="submit"
                                class="rounded-md bg-sello px-5 py-2.5 font-semibold text-white hover:bg-sello-oscuro">
                            {{ __('Search book') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Cómo funciona: es una secuencia real, por eso va numerada y unida por una ruta --}}
    <section id="como-funciona" class="border-t border-linea bg-papel-hondo/60">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <h2 class="font-display text-3xl font-bold tracking-tight">{{ __('How it works') }}</h2>

            <ol class="relative mt-12 grid gap-12 md:grid-cols-3 md:gap-8">
                {{-- Línea de la ruta que une los pasos (solo en escritorio) --}}
                <svg class="pointer-events-none absolute left-0 top-6 hidden h-4 w-full md:block" preserveAspectRatio="none" viewBox="0 0 100 4" aria-hidden="true">
                    <line x1="2" y1="2" x2="69" y2="2" stroke="#1e7a6e" stroke-width="0.6" stroke-dasharray="1.6 1.4" vector-effect="non-scaling-stroke" style="stroke-width:2px"/>
                </svg>

                @foreach ([
                    [__('Register the book'), __('Look the book up by its ISBN and zBooks gives it a unique code. Print the label and stick it inside the cover.')],
                    [__('Set it free'), __('Pick a visible spot: a bench, a station, a neighbourhood library. Mark it on the map so others can look for it.')],
                    [__('Someone finds it'), __('Whoever picks it up types the code, tells where they found it and, when they finish it, sets it free again.')],
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
            <h2 class="font-display text-xl font-bold">{{ __('zBooks is under construction') }}</h2>
            <p class="mt-2 text-tinta-suave">
                {{ __('It is a personal project built version by version. The grey buttons will come alive as their features arrive.') }}
                {{ __('You can follow its development on') }} <a href="https://github.com/zStrikke/zBooks" class="font-semibold text-sello underline underline-offset-4">GitHub</a>.
            </p>
        </div>
    </section>
</x-layouts::app>
