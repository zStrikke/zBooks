{{-- Indicador de carga de Mi zona (#18): un punto con ondas que se expanden, como un radar --}}
<span {{ $attributes->class('relative inline-flex size-5 shrink-0 items-center justify-center') }} aria-hidden="true">
    <span class="absolute inset-0 animate-ping rounded-full border-2 border-current opacity-60"></span>
    <span class="absolute inset-1 animate-ping rounded-full border border-current opacity-40 [animation-delay:300ms]"></span>
    <span class="size-1.5 rounded-full bg-current"></span>
</span>
