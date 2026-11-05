{{-- Cabecera: logotipo, navegación, buscador de ZBID y acceso --}}
<header x-data="{ abierto: false }" class="border-b border-linea bg-papel">
    <div class="mx-auto flex max-w-6xl items-center gap-6 px-4 py-4 sm:px-6">
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

        <div class="hidden items-center gap-4 font-display text-[0.95rem] font-medium lg:flex">
            @auth
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="font-semibold hover:text-sello">{{ auth()->user()->username }}</a>
                <a href="{{ route('account') }}" @class(['hover:text-sello', 'underline decoration-2 underline-offset-6' => request()->routeIs('account')])
                   @if (request()->routeIs('account')) aria-current="page" @endif>{{ __('My account') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-sello">{{ __('Log out') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-sello">{{ __('Sign in') }}</a>
                <a href="{{ route('register') }}" class="rounded-md bg-sello px-4 py-2 text-white hover:bg-sello-oscuro">{{ __('Create account') }}</a>
            @endauth
        </div>

        <button type="button" @click="abierto = !abierto" :aria-expanded="abierto" aria-controls="menu-movil"
                class="ml-auto rounded-md p-2 md:ml-0 lg:hidden">
            <span class="sr-only">{{ __('Open the menu') }}</span>
            <svg x-show="!abierto" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg x-show="abierto" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <div id="menu-movil" x-show="abierto" x-cloak class="border-t border-linea px-4 pb-6 pt-4 lg:hidden">
        <form action="{{ route('zbid.buscar') }}" method="GET" role="search" class="mb-4 md:hidden">
            <label for="zbid-movil" class="mb-1 block text-sm font-medium">{{ __('Book code') }}</label>
            <div class="flex rounded-md border border-linea bg-white focus-within:border-sello">
                <input id="zbid-movil" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                       class="min-w-0 flex-1 bg-transparent px-3 py-2 font-mono uppercase focus:outline-none">
                <button type="submit" class="px-4 font-semibold text-sello">{{ __('Search') }}</button>
            </div>
        </form>
        <nav aria-label="{{ __('Main (mobile)') }}" class="flex flex-col gap-3 font-display font-medium">
            <a href="{{ route('home') }}#como-funciona">{{ __('How it works') }}</a>
            <span class="text-tinta-suave/60">{{ __('Books nearby') }} ({{ __('version :version', ['version' => '0.5.0']) }})</span>
            <span class="text-tinta-suave/60">{{ __('Zones') }} ({{ __('version :version', ['version' => '0.5.0']) }})</span>
            @auth
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="font-semibold hover:text-sello">{{ auth()->user()->username }}</a>
                <a href="{{ route('account') }}">{{ __('My account') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">{{ __('Log out') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}">{{ __('Sign in') }}</a>
                <a href="{{ route('register') }}" class="text-sello">{{ __('Create account') }}</a>
            @endauth
        </nav>
    </div>
</header>
