{{-- Menú de usuario (#95): cerrar sesión y dar feedback (desactivado) --}}
@php
    $fila = 'flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-left hover:bg-papel focus:bg-papel focus:outline-none';
@endphp
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
