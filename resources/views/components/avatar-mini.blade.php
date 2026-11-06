{{-- Avatar redondo de la cabecera y del menú de usuario (#95): la foto o, si no hay, las iniciales --}}
@props(['user'])

@if ($user->avatar)
    <img src="{{ Storage::url($user->avatar) }}" alt="" {{ $attributes->class('size-10 shrink-0 rounded-full object-cover') }}>
@else
    <span aria-hidden="true" {{ $attributes->class('flex size-10 shrink-0 items-center justify-center rounded-full bg-etiqueta font-display text-sm font-extrabold text-tinta') }}>{{ $user->initials() }}</span>
@endif
