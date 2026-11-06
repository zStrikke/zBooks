<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? __('Leave a book in a public place and follow its journey around the world.') }}">

    <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') . ': ' . __('books that travel') }}</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Se cargan siempre: Alpine (incluido en Livewire) se usa también en páginas sin componentes --}}
    @livewireStyles
</head>
<body class="flex min-h-screen flex-col">
    <a href="#contenido"
       class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-tinta focus:px-4 focus:py-2 focus:text-papel">
        {{ __('Skip to content') }}
    </a>

    @include('partials.cabecera')

    {{-- Los avisos de Mi cuenta (#17) llegan como códigos y se muestran junto a su formulario --}}
    @if (session('status') && ! in_array(session('status'), ['profile-updated', 'avatar-removed', 'password-updated', 'zone-updated', 'zone-removed', 'zone-activated', 'zone-deactivated'], true))
        <div role="status" class="border-b border-ruta/30 bg-ruta/10">
            <p class="mx-auto max-w-6xl px-4 py-3 font-display font-semibold text-ruta sm:px-6">{{ session('status') }}</p>
        </div>
    @endif

    @auth
        @unless (auth()->user()->hasVerifiedEmail() || request()->routeIs('verification.notice'))
            <div class="border-b border-etiqueta/60 bg-etiqueta/25">
                <p class="mx-auto max-w-6xl px-4 py-3 text-[0.95rem] sm:px-6">
                    {{ __('Verify your email to register, release or catch books.') }}
                    <a href="{{ route('verification.notice') }}" class="font-display font-bold text-sello hover:text-sello-oscuro">{{ __('Resend the email') }}</a>
                </p>
            </div>
        @endunless
    @endauth

    <main id="contenido" class="flex-1">
        {{ $slot }}
    </main>

    @include('partials.pie')

    @livewireScripts
</body>
</html>
