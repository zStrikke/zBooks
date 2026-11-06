{{-- Menú de usuario (#95): saludo, cifras y estado de Mi zona. Lo usan el desplegable y el panel lateral de móvil --}}
@php
    $yo = auth()->user();
    $cifras = App\Users\ProfileStats::for($yo);
    $tieneZona = $yo->homeZone()->exists();
@endphp
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
