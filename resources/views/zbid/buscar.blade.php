<x-layouts::app title="Buscar un libro">
    <section class="mx-auto max-w-2xl px-4 py-20 sm:px-6">
        <h1 class="font-display text-3xl font-bold tracking-tight">Buscar un libro por su código</h1>

        @if ($codigo)
            <p class="mt-6">
                Has buscado <span class="rounded bg-etiqueta px-2 py-0.5 font-mono font-medium">{{ $codigo }}</span>.
            </p>
        @endif

        <p class="mt-4 text-tinta-suave">
            La búsqueda por código llegará en la versión 0.4.0, junto con la captura de libros.
            Guarda el código de la etiqueta: entonces podrás contar dónde encontraste el libro.
        </p>

        <a href="{{ route('home') }}" class="mt-8 inline-block font-display font-semibold text-sello underline underline-offset-4">
            Volver a la portada
        </a>
    </section>
</x-layouts::app>
