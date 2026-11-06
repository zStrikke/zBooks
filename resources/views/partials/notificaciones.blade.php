{{--
    Notificaciones (#95): la campana de la cabecera, junto al avatar. De momento
    solo enseña los tipos que llegarán, desactivados con su versión (épica #22).
--}}
@php
    $tipos = [
        [__('Private messages'), null, 'M4 6h16v12H4zM4 7l8 6 8-6'],
        [__('Your books, caught'), '0.5.0', 'M4 19.5V5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-1.5zM9 10l2 2 4-4'],
        [__('Books released near you'), '0.5.0', 'M12 21s-7-6.2-7-11a7 7 0 1 1 14 0c0 4.8-7 11-7 11zM12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z'],
        [__('Events'), '0.9.0', 'M4 6h16v14H4zM4 10h16M8 3v4M16 3v4'],
        [__('Friend requests'), '0.10.0', 'M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM3 20a6 6 0 0 1 12 0M19 8v6M16 11h6'],
    ];
@endphp

<div x-data="{ avisos: false }" @keydown.escape.window="avisos = false" @click.outside="avisos = false" class="relative">
    <button type="button" @click="avisos = ! avisos" :aria-expanded="avisos" aria-controls="menu-notificaciones" aria-haspopup="true"
            class="flex size-10 items-center justify-center rounded-full text-tinta-suave hover:bg-papel-hondo hover:text-tinta focus:outline-2 focus:outline-offset-2 focus:outline-sello">
        <span class="sr-only">{{ __('Your notifications') }}</span>
        <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a6 6 0 0 0-6 6v4.6L4.3 15.4A1 1 0 0 0 5.2 17h13.6a1 1 0 0 0 .9-1.6L18 12.6V8a6 6 0 0 0-6-6zM9.5 19a2.5 2.5 0 0 0 5 0z"/></svg>
    </button>

    <div id="menu-notificaciones" x-show="avisos" x-cloak x-transition.origin.top.right
         class="absolute right-0 z-[1100] mt-3 w-[min(19rem,calc(100vw-2rem))] rounded-2xl border border-linea bg-white p-2 font-display text-[0.95rem] shadow-[0_24px_48px_-24px_rgba(23,32,43,0.45)]">
        <p class="px-3 pb-3 pt-2.5 text-lg font-semibold text-ruta">{{ __('Your notifications') }}</p>
        <hr class="mx-3 border-linea">
        <ul class="flex flex-col py-2">
            @foreach ($tipos as [$texto, $version, $icono])
                <li class="flex cursor-not-allowed items-center gap-3 rounded-md px-3 py-2.5 text-tinta-suave/60"
                    title="{{ $version ? __('Available in version :version', ['version' => $version]) : __('Coming soon') }}">
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icono }}"/></svg>
                    {{ $texto }}
                    <span class="ml-auto shrink-0 font-mono text-xs">{{ $version ? 'v'.$version : __('soon') }}</span>
                </li>
            @endforeach
        </ul>
        <hr class="mx-3 border-linea">
        <p class="px-3 pb-2 pt-3 text-sm leading-snug text-tinta-suave">{{ __('Notifications arrive with the MVP (v0.5.0): you will know when someone catches one of your books.') }}</p>
    </div>
</div>
