{{-- Perfil público (#16). Diseño: docs/diseno/v0.2.0. Cifras, nunca qué libros ni dónde vive (ADR-0010) --}}
<x-layouts::app :title="$user->username" :description="__(':username on zBooks: books registered, released and caught.', ['username' => $user->username])">
    <div class="mx-auto flex max-w-6xl flex-wrap items-start gap-12 lg:px-6 lg:py-14">
        <aside aria-label="{{ __('Member card') }}" class="flex w-full flex-col gap-4 lg:max-w-100 lg:flex-1 lg:basis-85">
            {{-- Móvil: banda oscura a todo el ancho. Escritorio: el carné de socio --}}
            <div class="flex flex-col gap-4 bg-tinta px-5 pb-6 pt-7 text-papel lg:gap-5 lg:rounded-[14px] lg:p-8 lg:shadow-[0_30px_60px_-36px_rgba(23,32,43,0.6)]">
                <div class="hidden items-center justify-between lg:flex">
                    <p class="font-display text-[0.95rem] font-bold text-etiqueta">{{ __('zBooks member') }}</p>
                    <p class="font-mono text-sm text-[#aab3bf]">{{ __('since :date', ['date' => $user->created_at->isoFormat('MMM YYYY')]) }}</p>
                </div>

                <div class="flex items-center gap-4">
                    @if ($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                             class="h-19 w-19 shrink-0 rounded-full object-cover lg:h-22 lg:w-22">
                    @else
                        <div role="img" aria-label="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                             class="flex h-19 w-19 shrink-0 items-center justify-center rounded-full bg-etiqueta font-display text-3xl font-extrabold text-tinta lg:h-22 lg:w-22 lg:text-[2.125rem]">
                            {{ $user->initials() }}
                        </div>
                    @endif
                    <div class="flex min-w-0 flex-col gap-1">
                        <h1 class="break-all text-[1.75rem] font-extrabold leading-tight lg:text-4xl">{{ $user->username }}</h1>
                        <p class="text-sm text-[#c9d0d8] lg:hidden">{{ __('Member since :date', ['date' => $user->created_at->isoFormat('MMMM YYYY')]) }}</p>
                    </div>
                </div>

                @if ($user->bio)
                    <p class="whitespace-pre-line text-base leading-normal text-[#e4e8ec]">{{ $user->bio }}</p>
                @endif
            </div>

            <section aria-label="{{ __('Statistics') }}" class="grid grid-cols-3 gap-2.5 px-5 lg:px-0">
                @foreach ([
                    ['value' => $stats->registered, 'label' => __('books registered'), 'color' => 'text-sello'],
                    ['value' => $stats->released, 'label' => __('released'), 'color' => 'text-sello'],
                    ['value' => $stats->caught, 'label' => __('caught'), 'color' => 'text-ruta'],
                ] as $cifra)
                    <div class="flex flex-col gap-0.5 rounded-[10px] border border-linea bg-white px-3 py-3.5 lg:px-3.5 lg:py-4">
                        <p class="font-display text-3xl font-extrabold lg:text-[2rem] {{ $cifra['color'] }}">{{ $cifra['value'] }}</p>
                        <p class="text-sm leading-tight text-tinta-suave">{{ $cifra['label'] }}</p>
                    </div>
                @endforeach
            </section>

            <p class="hidden text-sm leading-normal text-tinta-suave lg:block">{{ __('For privacy, the profile shows numbers, never which books or where each member lives.') }}</p>
        </aside>

        <section class="flex min-w-0 flex-[999_1_32rem] flex-col gap-5 px-5 lg:px-0">
            <h2 class="text-[2rem] font-extrabold tracking-tight">{{ __('Blog') }}</h2>
            <div class="flex flex-col justify-center gap-3 rounded-[14px] border-2 border-dashed border-linea-fuerte px-6 py-10 lg:min-h-90 lg:px-10 lg:py-12">
                <svg class="h-10 w-10 text-sello" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h12v18H6a2 2 0 0 1-2-1.5z"/><path d="M8 7h6M8 11h6"/></svg>
                <p class="font-display text-[1.375rem] font-bold">{{ __('Their blog will be here') }}</p>
                <p class="max-w-130 leading-normal text-tinta-suave">{{ __('Each member will have a space to write about their reading and the books they set free. Coming in a future version.') }}</p>
            </div>
            <p class="pb-10 text-sm leading-normal text-tinta-suave lg:hidden">{{ __('For privacy, the profile shows numbers, never which books or where each member lives.') }}</p>
        </section>
    </div>
</x-layouts::app>
