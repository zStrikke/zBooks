<x-layouts::app :title="__('Search a book')">
    <section class="mx-auto max-w-2xl px-4 py-20 sm:px-6">
        <h1 class="font-display text-3xl font-bold tracking-tight">{{ __('Search a book by its code') }}</h1>

        @if ($codigo)
            <p class="mt-6">
                {{ __('You searched for') }} <span class="rounded bg-etiqueta px-2 py-0.5 font-mono font-medium">{{ $codigo }}</span>.
            </p>
        @endif

        <p class="mt-4 text-tinta-suave">
            {{ __('Search by code arrives in version 0.4.0, together with catching books. Keep the code on the label: then you will be able to tell where you found the book.') }}
        </p>

        <a href="{{ route('home') }}" class="mt-8 inline-block font-display font-semibold text-sello underline underline-offset-4">
            {{ __('Back to the home page') }}
        </a>
    </section>
</x-layouts::app>
