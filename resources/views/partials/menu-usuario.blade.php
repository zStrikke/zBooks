{{--
    Menú de usuario (#95): en escritorio, desplegable desde el avatar. En móvil, el avatar abre
    el panel lateral de la cabecera, con las mismas piezas (partials/usuario).
    Lo desactivado lleva la versión en que llega. Se cierra con Escape o al pulsar fuera.
--}}
@php
    $yo = auth()->user();
@endphp

<div x-data="{ menu: false }" @keydown.escape.window="menu = false" @click.outside="menu = false" class="relative">
    <button type="button" @click="menu = ! menu" :aria-expanded="menu" aria-controls="menu-usuario" aria-haspopup="true"
            class="flex rounded-full focus:outline-2 focus:outline-offset-2 focus:outline-sello">
        <span class="sr-only">{{ __('Open the menu of :username', ['username' => $yo->username]) }}</span>
        <x-avatar-mini :user="$yo" />
    </button>

    <div id="menu-usuario" x-show="menu" x-cloak x-transition.origin.top.right
         class="absolute right-0 z-[1100] mt-3 w-[min(19rem,calc(100vw-2rem))] rounded-2xl border border-linea bg-white p-2 font-display text-[0.95rem] shadow-[0_24px_48px_-24px_rgba(23,32,43,0.45)]">

        @include('partials.usuario.resumen')

        <hr class="mx-3 border-linea">

        @include('partials.usuario.enlaces')

        <hr class="mx-3 border-linea">

        @include('partials.usuario.salida')
    </div>
</div>
