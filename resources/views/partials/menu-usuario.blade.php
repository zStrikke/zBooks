{{--
    Menú de usuario (#95): se abre desde el avatar de la cabecera, en móvil y en escritorio.
    Lo desactivado lleva la versión en que llega. Se cierra con Escape o al pulsar fuera.
--}}
@php
    $yo = auth()->user();
    $cifras = App\Users\ProfileStats::for($yo);
    $tieneZona = $yo->homeZone()->exists();
    $proximamente = [
        [__('My books'), '0.3.0', 'M4 19.5V5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-1.5zM8 7h6'],
        [__('My catches'), '0.4.0', 'M11 5a6 6 0 1 0 0 12 6 6 0 0 0 0-12zM20 20l-4.5-4.5'],
        [__('Notifications'), '0.4.0', 'M6 16V11a6 6 0 1 1 12 0v5l2 2H4zM10 20a2 2 0 0 0 4 0'],
        [__('Friends'), '0.10.0', 'M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM3 20a6 6 0 0 1 12 0M16 11a3 3 0 1 0 0-6M17 14a5 5 0 0 1 4 6'],
    ];
    $fila = 'flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-left hover:bg-papel focus:bg-papel focus:outline-none';
@endphp

<div x-data="{ menu: false }" @keydown.escape.window="menu = false" @click.outside="menu = false" class="relative">
    <button type="button" @click="menu = ! menu" :aria-expanded="menu" aria-controls="menu-usuario" aria-haspopup="true"
            class="flex rounded-full focus:outline-2 focus:outline-offset-2 focus:outline-sello">
        <span class="sr-only">{{ __('Open the menu of :username', ['username' => $yo->username]) }}</span>
        @if ($yo->avatar)
            <img src="{{ Storage::url($yo->avatar) }}" alt="" class="size-10 rounded-full object-cover">
        @else
            <span aria-hidden="true" class="flex size-10 items-center justify-center rounded-full bg-etiqueta font-display text-sm font-extrabold text-tinta">{{ $yo->initials() }}</span>
        @endif
    </button>

    <div id="menu-usuario" x-show="menu" x-cloak x-transition.origin.top.right
         class="absolute right-0 z-[1100] mt-3 w-[min(19rem,calc(100vw-2rem))] rounded-2xl border border-linea bg-white p-2 font-display text-[0.95rem] shadow-[0_24px_48px_-24px_rgba(23,32,43,0.45)]">

        <div class="flex flex-col gap-3 px-3 pb-3 pt-2.5">
            <p class="truncate text-lg font-semibold text-ruta">{{ __('Hi, :username', ['username' => $yo->username]) }}</p>
            <div class="flex items-baseline justify-between">
                <p><span class="font-bold text-tinta">{{ $cifras->released }}</span> <span class="text-tinta-suave">{{ __('released') }}</span></p>
                <p><span class="font-bold text-tinta">{{ $cifras->caught }}</span> <span class="text-tinta-suave">{{ __('caught') }}</span></p>
            </div>
            {{-- No es un interruptor: enseña si hay zona y lleva a Mi zona para cambiarla --}}
            <a href="{{ route('account') }}#zona" class="-mx-3 flex items-center justify-between rounded-md px-3 py-1.5 hover:bg-papel">
                <span>{{ __('My zone') }}</span>
                <span class="flex items-center gap-2 text-sm text-tinta-suave">
                    {{ $tieneZona ? __('active') : __('not set') }}
                    <span aria-hidden="true" @class(['relative inline-flex h-4 w-8 items-center rounded-full', 'bg-ruta/30' => $tieneZona, 'bg-linea' => ! $tieneZona])>
                        <span @class(['absolute size-5 rounded-full border-2 border-white shadow', 'right-[-2px] bg-ruta' => $tieneZona, 'left-[-2px] bg-tinta-suave/50' => ! $tieneZona])></span>
                    </span>
                </span>
            </a>
        </div>

        <hr class="mx-3 border-linea">

        <nav aria-label="{{ __('Your account') }}" class="flex flex-col py-2">
            <a href="{{ route('profile.show', $yo->username) }}" class="{{ $fila }}">
                <svg class="size-5 text-tinta-suave" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0z"/></svg>
                {{ __('My profile') }}
            </a>
            <a href="{{ route('account') }}" class="{{ $fila }}" @if (request()->routeIs('account')) aria-current="page" @endif>
                <svg class="size-5 text-tinta-suave" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>
                {{ __('My account') }}
            </a>
            @foreach ($proximamente as [$texto, $version, $icono])
                <span class="{{ $fila }} cursor-not-allowed text-tinta-suave/60 hover:bg-transparent" title="{{ __('Available in version :version', ['version' => $version]) }}">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icono }}"/></svg>
                    {{ $texto }}
                    <span class="ml-auto font-mono text-xs">v{{ $version }}</span>
                </span>
            @endforeach
        </nav>

        <hr class="mx-3 border-linea">

        <div class="flex flex-col pt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="{{ $fila }}">
                    <svg class="size-5 text-tinta-suave" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12h8M13 8l4 4-4 4"/></svg>
                    {{ __('Sign out') }}
                </button>
            </form>
            <span class="{{ $fila }} cursor-not-allowed text-tinta-suave/60 hover:bg-transparent" title="{{ __('Coming soon') }}">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 1 1 3.5 2.3c-.6.3-1 .9-1 1.6M12 17h.01"/></svg>
                {{ __('Give feedback') }}
            </span>
        </div>
    </div>
</div>
