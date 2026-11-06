{{--
    Cabecera: logotipo, navegación, buscador de ZBID y acceso.
    Escritorio (lg): todo en una fila; con sesión, campana y avatar con desplegable (#95).
    Móvil: con sesión, el avatar a la izquierda abre un panel lateral con el menú de usuario,
    la navegación y el buscador; sin sesión, la hamburguesa abre el mismo panel.
--}}
<header x-data="{ abierto: false }" x-effect="document.body.classList.toggle('overflow-hidden', abierto)"
        @keydown.escape.window="abierto = false" class="border-b border-linea bg-papel">
    <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 py-3 sm:px-6 lg:gap-6 lg:py-4">
        @auth
            {{-- Móvil: el avatar abre el panel lateral --}}
            <button type="button" @click="abierto = true" :aria-expanded="abierto" aria-controls="panel-movil"
                    class="flex shrink-0 rounded-full focus:outline-2 focus:outline-offset-2 focus:outline-sello lg:hidden">
                <span class="sr-only">{{ __('Open the menu of :username', ['username' => auth()->user()->username]) }}</span>
                <x-avatar-mini :user="auth()->user()" />
            </button>
        @endauth

        <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-2xl font-bold tracking-tight">
            <x-logo class="h-8 w-8" />
            <span>zBooks</span>
        </a>

        <nav aria-label="{{ __('Main') }}" class="hidden items-center gap-6 text-[0.95rem] font-medium lg:flex">
            <a href="{{ route('home') }}#como-funciona" class="hover:text-sello">{{ __('How it works') }}</a>
            <span class="cursor-not-allowed text-tinta-suave/60" title="{{ __('Available in version :version', ['version' => '0.5.0']) }}">{{ __('Books nearby') }}</span>
            <span class="cursor-not-allowed text-tinta-suave/60" title="{{ __('Available in version :version', ['version' => '0.5.0']) }}">{{ __('Zones') }}</span>
        </nav>

        <form action="{{ route('zbid.buscar') }}" method="GET" role="search" class="ml-auto hidden md:block">
            <label for="zbid-cabecera" class="sr-only">{{ __('Book ZBID code') }}</label>
            <div class="flex items-center rounded-md border border-linea bg-white focus-within:border-sello">
                <input id="zbid-cabecera" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                       placeholder="{{ __('Book code') }}"
                       class="w-44 bg-transparent px-3 py-1.5 font-mono text-sm uppercase placeholder:font-display placeholder:normal-case placeholder:text-tinta-suave focus:outline-none">
                <button type="submit" class="px-3 py-1.5 text-sm font-semibold text-sello hover:text-sello-oscuro">
                    {{ __('Search') }}
                </button>
            </div>
        </form>

        @auth
            <div class="ml-auto flex items-center gap-1 md:ml-0">
                @include('partials.notificaciones')
                <div class="hidden lg:block">
                    @include('partials.menu-usuario')
                </div>
            </div>
        @else
            <div class="hidden items-center gap-4 font-display text-[0.95rem] font-medium lg:flex">
                <a href="{{ route('login') }}" class="hover:text-sello">{{ __('Sign in') }}</a>
                <a href="{{ route('register') }}" class="rounded-md bg-sello px-4 py-2 text-white hover:bg-sello-oscuro">{{ __('Create account') }}</a>
            </div>
            <button type="button" @click="abierto = true" :aria-expanded="abierto" aria-controls="panel-movil"
                    class="ml-auto rounded-md p-2 md:ml-0 lg:hidden">
                <span class="sr-only">{{ __('Open the menu') }}</span>
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        @endauth
    </div>

    {{-- Panel lateral de móvil (#95): se cierra con Escape, la ✕ o tocando fuera --}}
    <div x-show="abierto" x-cloak class="fixed inset-0 z-[1200] lg:hidden">
        <div x-show="abierto" x-transition.opacity @click="abierto = false" class="absolute inset-0 bg-tinta/40" aria-hidden="true"></div>

        <div id="panel-movil" x-show="abierto" role="dialog" aria-modal="true" aria-label="{{ __('Menu') }}"
             x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             x-trap.noscroll="abierto"
             class="absolute inset-y-0 left-0 flex w-[min(20rem,85vw)] flex-col overflow-y-auto bg-white font-display text-[0.95rem] shadow-xl">

            <button type="button" @click="abierto = false" class="absolute right-2 top-2 rounded-md p-2 text-tinta-suave hover:text-tinta">
                <span class="sr-only">{{ __('Close the menu') }}</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>

            @auth
                <div class="flex flex-col items-center gap-1 px-4 pt-6">
                    <x-avatar-mini :user="auth()->user()" class="size-16 text-xl" />
                </div>
                <div class="px-2 text-center [&_.justify-between]:text-left">
                    @include('partials.usuario.resumen')
                </div>
                <hr class="mx-5 border-linea">
                <div class="px-2">
                    @include('partials.usuario.enlaces')
                </div>
                <hr class="mx-5 border-linea">
            @endauth

            <nav aria-label="{{ __('Main (mobile)') }}" class="flex flex-col px-2 py-2">
                <a href="{{ route('home') }}#como-funciona" @click="abierto = false" class="rounded-md px-3 py-2.5 hover:bg-papel">{{ __('How it works') }}</a>
                <span class="flex items-center rounded-md px-3 py-2.5 text-tinta-suave/60">{{ __('Books nearby') }}<span class="ml-auto font-mono text-xs">v0.5.0</span></span>
                <span class="flex items-center rounded-md px-3 py-2.5 text-tinta-suave/60">{{ __('Zones') }}<span class="ml-auto font-mono text-xs">v0.5.0</span></span>
            </nav>

            <form action="{{ route('zbid.buscar') }}" method="GET" role="search" class="px-5 pb-4 md:hidden">
                <label for="zbid-movil" class="mb-1 block text-sm font-medium">{{ __('Book code') }}</label>
                <div class="flex rounded-md border border-linea bg-white focus-within:border-sello">
                    <input id="zbid-movil" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                           class="min-w-0 flex-1 bg-transparent px-3 py-2 font-mono uppercase focus:outline-none">
                    <button type="submit" class="px-4 font-semibold text-sello">{{ __('Search') }}</button>
                </div>
            </form>

            <div class="mt-auto border-t border-linea px-2 py-2">
                @auth
                    @include('partials.usuario.salida')
                @else
                    <div class="flex flex-col gap-2 p-3">
                        <a href="{{ route('login') }}" class="rounded-md border-2 border-tinta px-4 py-2.5 text-center font-semibold">{{ __('Sign in') }}</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-sello px-4 py-2.5 text-center font-semibold text-white">{{ __('Create account') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
