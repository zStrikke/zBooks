{{-- Menú de usuario (#95): Mi perfil, Mi cuenta y lo que aún no existe, desactivado con su versión --}}
@php
    $yo = auth()->user();
    $proximamente = [
        [__('My books'), '0.3.0', 'M4 19.5V5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-1.5zM8 7h6'],
        [__('My catches'), '0.4.0', 'M11 5a6 6 0 1 0 0 12 6 6 0 0 0 0-12zM20 20l-4.5-4.5'],
        [__('Friends'), '0.10.0', 'M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM3 20a6 6 0 0 1 12 0M16 11a3 3 0 1 0 0-6M17 14a5 5 0 0 1 4 6'],
    ];
    $fila = 'flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-left hover:bg-papel focus:bg-papel focus:outline-none';
@endphp
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
