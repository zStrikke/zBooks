{{--
    Interruptor de Mi zona (#99): apaga o enciende la zona sin borrarla. Es un formulario,
    así que funciona sin JavaScript, y vuelve a la página en la que estabas.
    Sin zona guardada no se puede usar: lleva a Mi zona para elegirla.
--}}
@props(['zone', 'label' => __('My zone'), 'id' => 'interruptor-zona'])

@php
    $activa = (bool) $zone?->is_active;
@endphp

@if ($zone)
    <form method="POST" action="{{ route('account.zone.toggle') }}" {{ $attributes->class('flex items-center justify-between gap-3') }}>
        @csrf
        @method('PATCH')
        <input type="hidden" name="active" value="{{ $activa ? 0 : 1 }}">
        <span id="{{ $id }}">{{ $label }}</span>
        <button type="submit" role="switch" aria-checked="{{ $activa ? 'true' : 'false' }}"
                aria-labelledby="{{ $id }}"
                class="flex items-center gap-2 rounded-full text-sm text-tinta-suave focus:outline-2 focus:outline-offset-2 focus:outline-sello">
            {{ $activa ? __('active') : __('off') }}
            <span aria-hidden="true" @class(['relative inline-flex h-4 w-8 items-center rounded-full transition-colors', 'bg-ruta/30' => $activa, 'bg-linea' => ! $activa])>
                <span @class(['absolute size-5 rounded-full border-2 border-white shadow', 'right-[-2px] bg-ruta' => $activa, 'left-[-2px] bg-tinta-suave/50' => ! $activa])></span>
            </span>
        </button>
    </form>
@else
    <a href="{{ route('account') }}#zona" {{ $attributes->class('flex items-center justify-between gap-3 hover:text-sello') }}>
        <span>{{ $label }}</span>
        <span class="flex items-center gap-2 text-sm text-tinta-suave">
            {{ __('choose it') }}
            <span aria-hidden="true" class="relative inline-flex h-4 w-8 items-center rounded-full bg-linea opacity-60">
                <span class="absolute left-[-2px] size-5 rounded-full border-2 border-white bg-tinta-suave/50 shadow"></span>
            </span>
        </span>
    </a>
@endif
