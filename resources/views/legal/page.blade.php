{{-- Presentación común de los textos legales. El contenido está en resources/markdown/legal/ --}}
<x-layouts::app :title="$document->title()" :description="$document->description()">
    <article class="mx-auto max-w-3xl px-4 py-16 sm:px-6
                    [&_a]:text-sello [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-sello-oscuro
                    [&_h1]:font-display [&_h1]:text-4xl [&_h1]:font-extrabold [&_h1]:tracking-tight
                    [&_h1+p]:mt-3 [&_h1+p]:border-b [&_h1+p]:border-linea [&_h1+p]:pb-8 [&_h1+p]:text-tinta-suave
                    [&_h2]:mt-10 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold
                    [&_li]:mt-2 [&_p]:mt-3 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:pl-6">
        {{ $document->html() }}
    </article>
</x-layouts::app>
