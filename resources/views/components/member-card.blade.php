{{--
    Ficha de socio (#16): tapa de tela con filete dorado, sello de alta y firma.
    - size: «auto» se adapta a la pantalla (perfil público); «movil» y «escritorio»
      fijan uno de los dos tamaños (vista previa de Mi cuenta, #17).
    - live: la bio y el avatar salen de las variables Alpine «bio» y «avatarUrl»
      del formulario que la contiene, para verla cambiar mientras se escribe.
--}}
@props(['user', 'size' => 'auto', 'live' => false])

@php
    [$avatar, $nombre] = match ($size) {
        'movil' => ['h-19 w-19', 'text-[1.75rem]'],
        'escritorio' => ['h-21 w-21', 'text-[1.875rem]'],
        default => ['h-19 w-19 lg:h-21 lg:w-21', 'text-[1.75rem] lg:text-[1.875rem]'],
    };
    // Ids propios: la misma página puede tener varias fichas y el filtro de una oculta no se aplicaría
    $id = 'sello-'.Str::random(6);
    $avatarUrl = $user->avatar ? Storage::url($user->avatar) : null;
@endphp

<div {{ $attributes->class('rounded-xl bg-tela p-2 shadow-[0_30px_60px_-32px_rgba(23,32,43,0.6)]') }}>
    <div class="relative flex flex-col gap-4 overflow-hidden rounded-lg border-[1.5px] border-filete bg-[repeating-linear-gradient(45deg,rgba(255,255,255,0.025)_0_2px,transparent_2px_5px)] px-5 pb-4.5 pt-5 text-papel">
        <div class="-mt-1 flex items-baseline justify-center gap-2.5 border-b border-filete/60 pb-2">
            <p class="font-display text-xs font-bold uppercase tracking-[3px] text-filete">{{ __('Member file') }}</p>
            <p class="font-display text-lg font-extrabold text-filete-claro">zBooks</p>
        </div>

        <div class="flex items-center gap-4.5">
            @if ($live)
                <img x-show="avatarUrl" :src="avatarUrl" src="{{ $avatarUrl }}" @style(['display: none' => ! $avatarUrl])
                     alt="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                     class="{{ $avatar }} shrink-0 rounded-full object-cover outline-2 outline-offset-3 outline-filete">
            @elseif ($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                     class="{{ $avatar }} shrink-0 rounded-full object-cover outline-2 outline-offset-3 outline-filete">
            @endif
            @if ($live || ! $avatarUrl)
                <div role="img" aria-label="{{ __('Avatar of :username', ['username' => $user->username]) }}"
                     @if ($live) x-show="! avatarUrl" @style(['display: none' => (bool) $avatarUrl]) @endif
                     class="{{ $avatar }} flex shrink-0 items-center justify-center rounded-full bg-etiqueta font-display text-3xl font-extrabold text-tinta outline-2 outline-offset-3 outline-filete">
                    {{ $user->initials() }}
                </div>
            @endif
            <div class="flex min-w-0 flex-col gap-1.5">
                {{-- En el perfil es el título de la página; en la vista previa, un texto más --}}
                <{{ $live ? 'p' : 'h1' }} class="break-all font-display {{ $nombre }} font-extrabold leading-tight">{{ $user->username }}</{{ $live ? 'p' : 'h1' }}>
                <p class="font-mono text-[0.8125rem] text-[#d8cfae]">
                    <span class="whitespace-nowrap">{{ __('No. :number', ['number' => $user->memberNumber()]) }}</span> ·
                    <span class="whitespace-nowrap">{{ __('since :date', ['date' => $user->created_at->isoFormat('MMM YYYY')]) }}</span>
                </p>
            </div>
        </div>

        @if ($live)
            <p x-show="bio.trim() !== ''" x-text="'«' + bio.trim() + '»'" @style(['display: none' => blank($user->bio)])
               class="whitespace-pre-line text-[0.9375rem] italic leading-normal text-[#e4e8ec]">«{{ $user->bio }}»</p>
        @elseif ($user->bio)
            <p class="whitespace-pre-line text-[0.9375rem] italic leading-normal text-[#e4e8ec]">«{{ $user->bio }}»</p>
        @endif

        {{-- Sello de alta puesto deprisa en la esquina (lo corta el filete, con la tinta gastada) y el username firmado --}}
        <div class="flex min-h-14.5 items-end justify-end border-t border-filete/60 pt-2.5">
            <svg aria-hidden="true" viewBox="0 0 100 100" class="absolute -bottom-9 -left-6 size-28 -rotate-24 text-filete-claro opacity-80">
                <defs>
                    <path id="{{ $id }}-aro" d="M50,50 m-37,0 a37,37 0 1,1 74,0 a37,37 0 1,1 -74,0"/>
                    {{-- Desgaste: ruido que se come la tinta a parches, más un poco de temblor en los bordes --}}
                    <filter id="{{ $id }}-gastado" x="-10%" y="-10%" width="120%" height="120%">
                        <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" seed="7" result="grano"/>
                        <feTurbulence type="fractalNoise" baseFrequency="0.06" numOctaves="2" seed="3" result="manchas"/>
                        <feComposite in="grano" in2="manchas" operator="arithmetic" k2="0.6" k3="0.9" k4="-0.35" result="ruido"/>
                        <feColorMatrix in="ruido" type="matrix" values="0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 -2.4 1.6" result="mascara"/>
                        <feComposite in="SourceGraphic" in2="mascara" operator="in" result="tinta"/>
                        <feDisplacementMap in="tinta" in2="grano" scale="1.6"/>
                    </filter>
                </defs>
                <g filter="url(#{{ $id }}-gastado)">
                    <circle cx="50" cy="50" r="47" fill="none" stroke="currentColor" stroke-width="2.5"/>
                    <circle cx="50" cy="50" r="29" fill="none" stroke="currentColor" stroke-width="1.2"/>
                    <text font-family="IBM Plex Mono, monospace" font-size="9.5" font-weight="500" letter-spacing="1.6" fill="currentColor"><textPath href="#{{ $id }}-aro">{{ mb_strtoupper(__('zBooks · approved member · zBooks ·')) }}</textPath></text>
                    <text x="50" y="47" text-anchor="middle" font-family="IBM Plex Mono, monospace" font-size="9" font-weight="500" fill="currentColor">{{ mb_strtoupper(rtrim($user->created_at->isoFormat('MMM'), '.')) }}</text>
                    <text x="50" y="60" text-anchor="middle" font-family="IBM Plex Mono, monospace" font-size="12" font-weight="500" fill="currentColor">{{ $user->created_at->year }}</text>
                </g>
            </svg>
            <p aria-hidden="true" class="-mb-1.5 max-w-[60%] -rotate-4 truncate font-firma text-[2.75rem] leading-none">{{ $user->username }}</p>
        </div>
    </div>
</div>
