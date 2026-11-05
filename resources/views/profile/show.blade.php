{{-- Perfil público (#16). Diseño: docs/diseno/v0.2.0. Cifras, nunca qué libros ni dónde vive (ADR-0010) --}}
<x-layouts::app :title="$user->username" :description="__(':username on zBooks: books registered, released and caught.', ['username' => $user->username])">
    <div class="mx-auto flex max-w-6xl flex-wrap items-start gap-12 lg:px-6 lg:py-14">
        <aside aria-label="{{ __('Member card') }}" class="flex w-full flex-col gap-4 lg:max-w-100 lg:flex-1 lg:basis-85">
            {{-- Ficha de socio: tapa de tela con filete dorado, sello de alta y firma (diseño elegido en #76) --}}
            <div class="mx-5 mt-6 rounded-xl bg-tela p-2 shadow-[0_30px_60px_-32px_rgba(23,32,43,0.6)] lg:mx-0 lg:mt-0">
                <div class="relative flex flex-col gap-4 overflow-hidden rounded-lg border-[1.5px] border-filete bg-[repeating-linear-gradient(45deg,rgba(255,255,255,0.025)_0_2px,transparent_2px_5px)] px-5 pb-4.5 pt-5 text-papel">
                    <div class="-mt-1 flex items-baseline justify-center gap-2.5 border-b border-filete/60 pb-2">
                        <p class="font-display text-xs font-bold uppercase tracking-[3px] text-filete">{{ __('Member file') }}</p>
                        <p class="font-display text-lg font-extrabold text-filete-claro">zBooks</p>
                    </div>

                    <div class="flex items-center gap-4.5">
                        @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                                 class="h-19 w-19 shrink-0 rounded-full object-cover outline-2 outline-offset-3 outline-filete lg:h-21 lg:w-21">
                        @else
                            <div role="img" aria-label="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                                 class="flex h-19 w-19 shrink-0 items-center justify-center rounded-full bg-etiqueta font-display text-3xl font-extrabold text-tinta outline-2 outline-offset-3 outline-filete lg:h-21 lg:w-21">
                                {{ $user->initials() }}
                            </div>
                        @endif
                        <div class="flex min-w-0 flex-col gap-1.5">
                            <h1 class="break-all text-[1.75rem] font-extrabold leading-tight lg:text-[1.875rem]">{{ $user->username }}</h1>
                            <p class="font-mono text-[0.8125rem] text-[#d8cfae]"><span class="whitespace-nowrap">{{ __('No. :number', ['number' => $user->memberNumber()]) }}</span> · <span class="whitespace-nowrap">{{ __('since :date', ['date' => $user->created_at->isoFormat('MMM YYYY')]) }}</span></p>
                        </div>
                    </div>

                    @if ($user->bio)
                        <p class="whitespace-pre-line text-[0.9375rem] italic leading-normal text-[#e4e8ec]">«{{ $user->bio }}»</p>
                    @endif

                    {{-- Sello de alta puesto deprisa en la esquina (lo corta el filete, con la tinta gastada) y el username firmado --}}
                    <div class="flex min-h-14.5 items-end justify-end border-t border-filete/60 pt-2.5">
                        <svg aria-hidden="true" viewBox="0 0 100 100" class="absolute -bottom-9 -left-6 size-28 -rotate-24 text-filete-claro opacity-80">
                            <defs>
                                <path id="sello-aro" d="M50,50 m-37,0 a37,37 0 1,1 74,0 a37,37 0 1,1 -74,0"/>
                                {{-- Desgaste: ruido que se come la tinta a parches, más un poco de temblor en los bordes --}}
                                <filter id="sello-gastado" x="-10%" y="-10%" width="120%" height="120%">
                                    <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" seed="7" result="grano"/>
                                    <feTurbulence type="fractalNoise" baseFrequency="0.06" numOctaves="2" seed="3" result="manchas"/>
                                    <feComposite in="grano" in2="manchas" operator="arithmetic" k2="0.6" k3="0.9" k4="-0.35" result="ruido"/>
                                    <feColorMatrix in="ruido" type="matrix" values="0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 -2.4 1.6" result="mascara"/>
                                    <feComposite in="SourceGraphic" in2="mascara" operator="in" result="tinta"/>
                                    <feDisplacementMap in="tinta" in2="grano" scale="1.6"/>
                                </filter>
                            </defs>
                            <g filter="url(#sello-gastado)">
                            <circle cx="50" cy="50" r="47" fill="none" stroke="currentColor" stroke-width="2.5"/>
                            <circle cx="50" cy="50" r="29" fill="none" stroke="currentColor" stroke-width="1.2"/>
                            <text font-family="IBM Plex Mono, monospace" font-size="9.5" font-weight="500" letter-spacing="1.6" fill="currentColor"><textPath href="#sello-aro">{{ mb_strtoupper(__('zBooks · approved member · zBooks ·')) }}</textPath></text>
                            <text x="50" y="47" text-anchor="middle" font-family="IBM Plex Mono, monospace" font-size="9" font-weight="500" fill="currentColor">{{ mb_strtoupper(rtrim($user->created_at->isoFormat('MMM'), '.')) }}</text>
                            <text x="50" y="60" text-anchor="middle" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="500" fill="currentColor">{{ $user->created_at->year }}</text>
                            </g>
                        </svg>
                        <p aria-hidden="true" class="-mb-1.5 max-w-[60%] -rotate-4 truncate font-firma text-[2.75rem] leading-none">{{ $user->username }}</p>
                    </div>
                </div>
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
