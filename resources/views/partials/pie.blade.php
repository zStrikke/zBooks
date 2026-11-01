{{-- Pie de página --}}
<footer class="mt-24 border-t border-linea bg-papel-hondo">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 text-[0.95rem] sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex items-center gap-2 font-display font-semibold">
            <x-logo class="h-6 w-6" />
            <span>zBooks</span>
            <span class="font-normal text-tinta-suave">versión {{ config('app.version') }}</span>
        </div>
        <ul class="flex flex-wrap gap-x-6 gap-y-2 font-display text-tinta-suave">
            <li><a href="{{ route('home') }}#como-funciona" class="hover:text-tinta">Cómo funciona</a></li>
            <li><a href="https://github.com/zStrikke/zBooks" class="hover:text-tinta">Código fuente</a></li>
            <li><a href="{{ route('legal.privacy') }}" class="hover:text-tinta">Privacidad</a></li>
            <li><a href="{{ route('legal.notice') }}" class="hover:text-tinta">Aviso legal</a></li>
        </ul>
    </div>
</footer>
