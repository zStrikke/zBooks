<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? 'Deja un libro en un lugar público y sigue su viaje por el mundo.' }}">

    <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') . ': libros que viajan' }}</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Se cargan siempre: Alpine (incluido en Livewire) se usa también en páginas sin componentes --}}
    @livewireStyles
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenido"
       class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-tinta focus:px-4 focus:py-2 focus:text-papel">
        Saltar al contenido
    </a>

    @include('partials.cabecera')

    <main id="contenido" class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.pie')

    @livewireScripts
</body>
</html>
