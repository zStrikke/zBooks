{{-- Cabecera: logotipo, navegación, buscador de ZBID y acceso --}}
<header x-data="{ abierto: false }" class="border-b border-linea bg-papel">
    <div class="mx-auto flex max-w-6xl items-center gap-6 px-4 py-4 sm:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-2xl font-bold tracking-tight">
            <x-logo class="h-8 w-8" />
            <span>zBooks</span>
        </a>

        <nav aria-label="Principal" class="hidden items-center gap-6 text-[0.95rem] font-medium lg:flex">
            <a href="{{ route('home') }}#como-funciona" class="hover:text-sello">Cómo funciona</a>
            <span class="cursor-not-allowed text-tinta-suave/60" title="Disponible en la versión 0.5.0">Libros cerca</span>
            <span class="cursor-not-allowed text-tinta-suave/60" title="Disponible en la versión 0.5.0">Zonas</span>
        </nav>

        <form action="{{ route('zbid.buscar') }}" method="GET" role="search" class="ml-auto hidden md:block">
            <label for="zbid-cabecera" class="sr-only">Código ZBID del libro</label>
            <div class="flex items-center rounded-md border border-linea bg-white focus-within:border-sello">
                <input id="zbid-cabecera" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                       placeholder="Código del libro"
                       class="w-44 bg-transparent px-3 py-1.5 font-mono text-sm uppercase placeholder:font-display placeholder:normal-case placeholder:text-tinta-suave focus:outline-none">
                <button type="submit" class="px-3 py-1.5 text-sm font-semibold text-sello hover:text-sello-oscuro">
                    Buscar
                </button>
            </div>
        </form>

        <div class="hidden items-center gap-4 font-display text-[0.95rem] font-medium lg:flex">
            <span class="cursor-not-allowed text-tinta-suave/60" title="Disponible en la versión 0.2.0">Entrar</span>
            <span class="cursor-not-allowed rounded-md bg-sello/50 px-4 py-2 text-white" title="Disponible en la versión 0.2.0">Crear cuenta</span>
        </div>

        <button type="button" @click="abierto = !abierto" :aria-expanded="abierto" aria-controls="menu-movil"
                class="ml-auto rounded-md p-2 md:ml-0 lg:hidden">
            <span class="sr-only">Abrir el menú</span>
            <svg x-show="!abierto" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg x-show="abierto" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <div id="menu-movil" x-show="abierto" x-cloak class="border-t border-linea px-4 pb-6 pt-4 lg:hidden">
        <form action="{{ route('zbid.buscar') }}" method="GET" role="search" class="mb-4 md:hidden">
            <label for="zbid-movil" class="mb-1 block text-sm font-medium">Código del libro</label>
            <div class="flex rounded-md border border-linea bg-white focus-within:border-sello">
                <input id="zbid-movil" name="codigo" type="text" required autocomplete="off" spellcheck="false"
                       class="min-w-0 flex-1 bg-transparent px-3 py-2 font-mono uppercase focus:outline-none">
                <button type="submit" class="px-4 font-semibold text-sello">Buscar</button>
            </div>
        </form>
        <nav aria-label="Principal (móvil)" class="flex flex-col gap-3 font-display font-medium">
            <a href="{{ route('home') }}#como-funciona">Cómo funciona</a>
            <span class="text-tinta-suave/60">Libros cerca (versión 0.5.0)</span>
            <span class="text-tinta-suave/60">Zonas (versión 0.5.0)</span>
            <span class="text-tinta-suave/60">Entrar y crear cuenta (versión 0.2.0)</span>
        </nav>
    </div>
</header>
