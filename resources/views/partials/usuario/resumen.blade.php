{{-- Menú de usuario (#95): saludo, cifras y estado de Mi zona. Lo usan el desplegable y el panel lateral de móvil --}}
@php
    $yo = auth()->user();
    $cifras = App\Users\ProfileStats::for($yo);
    $zona = $yo->homeZone()->first();
@endphp
<div class="flex flex-col gap-3 px-3 pb-3 pt-2.5">
    <p class="truncate text-lg font-semibold text-ruta">{{ __('Hi, :username', ['username' => $yo->username]) }}</p>
    <div class="flex items-baseline justify-between">
        <p><span class="font-bold text-tinta">{{ $cifras->released }}</span> <span class="text-tinta-suave">{{ __('released') }}</span></p>
        <p><span class="font-bold text-tinta">{{ $cifras->caught }}</span> <span class="text-tinta-suave">{{ __('caught') }}</span></p>
    </div>
    {{-- Interruptor de verdad (#99): apaga o enciende la zona; sin zona, lleva a elegirla.
         El id cambia porque el desplegable y el panel de móvil están a la vez en la página --}}
    <x-interruptor-zona :zone="$zona" :id="'interruptor-zona-'.Str::random(4)" class="-mx-3 rounded-md px-3 py-1.5" />
</div>
