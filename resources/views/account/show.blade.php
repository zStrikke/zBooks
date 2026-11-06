{{-- Mi cuenta (#17) y Mi zona (#18). Diseño: docs/diseno/v0.2.0 (Mi cuenta, Mi zona) --}}
@php
    use App\Actions\Users\UpdateProfile;
    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\ZoneController;
    use App\Models\UserZone;
    use Laravel\Fortify\Fortify;

    $estado = session('status');
    $errorPerfil = $errors->updateProfile;
    $errorClave = $errors->updatePassword;
    $errorZona = $errors->updateZone;
    $zona = $user->homeZone;
@endphp

<x-layouts::app :title="__('My account')">
    <div class="mx-auto grid max-w-7xl gap-5 px-4 py-8 sm:px-6 lg:grid-cols-[11rem_minmax(0,1fr)] lg:gap-x-10 lg:gap-y-6 lg:py-12 xl:grid-cols-[11rem_minmax(0,1fr)_22rem]">

        {{-- Título y secciones: arriba en móvil, columna fija a la izquierda en escritorio --}}
        <div class="flex flex-col gap-4 lg:sticky lg:top-6 lg:row-span-4 lg:self-start lg:gap-5">
            <h1 class="text-[2rem] font-extrabold leading-none tracking-tight lg:text-4xl">{{ __('My account') }}</h1>
            <nav aria-label="{{ __('Account sections') }}"
                 class="-mx-4 flex gap-2 overflow-x-auto px-4 font-display text-[0.95rem] font-bold sm:mx-0 sm:px-0 lg:flex-col lg:gap-0 lg:border-l-2 lg:border-linea lg:text-[1.0625rem]">
                <a href="#perfil" class="shrink-0 rounded-full border-[1.5px] border-linea-fuerte px-3.5 py-2 hover:border-tinta lg:-ml-0.5 lg:rounded-none lg:border-0 lg:border-l-[3px] lg:border-transparent lg:px-4 lg:hover:border-sello">{{ __('Profile') }}</a>
                <a href="#contrasena" class="shrink-0 rounded-full border-[1.5px] border-linea-fuerte px-3.5 py-2 hover:border-tinta lg:-ml-0.5 lg:rounded-none lg:border-0 lg:border-l-[3px] lg:border-transparent lg:px-4 lg:hover:border-sello">{{ __('Password') }}</a>
                <a href="#zona" class="shrink-0 rounded-full border-[1.5px] border-linea-fuerte px-3.5 py-2 hover:border-tinta lg:-ml-0.5 lg:rounded-none lg:border-0 lg:border-l-[3px] lg:border-transparent lg:px-4 lg:hover:border-sello">{{ __('My zone') }}</a>
            </nav>
            <a href="{{ route('profile.show', $user->username) }}" class="hidden font-display text-[0.95rem] font-semibold text-sello underline hover:text-sello-oscuro lg:block">{{ __('See my profile') }}</a>
        </div>

        {{-- Perfil + vista previa comparten el estado de Alpine: la ficha cambia mientras se escribe --}}
        <div x-data="{
                bio: @js(old('bio', $user->bio ?? '')),
                avatarUrl: @js($user->avatar ? Storage::url($user->avatar) : null),
                elegir(evento) {
                    const fichero = evento.target.files[0];
                    if (fichero) this.avatarUrl = URL.createObjectURL(fichero);
                },
             }"
             class="contents">

            <section id="perfil" aria-labelledby="perfil-titulo" class="flex scroll-mt-6 flex-col gap-5 rounded-[14px] border border-linea bg-white p-5 lg:col-start-2 lg:p-8">
                <div>
                    <h2 id="perfil-titulo" class="text-2xl font-extrabold">{{ __('Profile') }}</h2>
                    <p class="mt-1 text-[0.95rem] text-tinta-suave">
                        {{ __('What you see on') }} <a href="{{ route('profile.show', $user->username) }}" class="text-sello underline hover:text-sello-oscuro">{{ __('your profile') }}</a>{{ __(', and your friends will see when they arrive.') }}
                    </p>
                </div>

                <form id="form-perfil" method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="flex flex-col gap-5">
                    @csrf
                    @method('PATCH')

                    <div class="flex flex-wrap items-center gap-x-5 gap-y-3">
                        <img x-show="avatarUrl" :src="avatarUrl" src="{{ $user->avatar ? Storage::url($user->avatar) : '' }}" @style(['display: none' => ! $user->avatar])
                             alt="{{ __('Your avatar') }}" class="size-22 shrink-0 rounded-full object-cover">
                        <div x-show="! avatarUrl" @style(['display: none' => (bool) $user->avatar]) aria-hidden="true"
                             class="flex size-22 shrink-0 items-center justify-center rounded-full bg-etiqueta font-display text-[2rem] font-extrabold">
                            {{ $user->initials() }}
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <label for="avatar" class="inline-flex h-11 cursor-pointer items-center rounded-lg border-2 border-tinta px-4 text-[0.95rem] font-bold focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-sello hover:bg-papel">
                                {{ __('Change photo') }}
                                <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp" @change="elegir($event)"
                                       aria-describedby="avatar-ayuda @if ($errorPerfil->has('avatar')) avatar-error @endif"
                                       class="sr-only">
                            </label>
                            @if ($user->avatar)
                                <button type="submit" form="form-quitar-avatar" class="font-display text-[0.95rem] font-semibold text-error underline">{{ __('Remove photo') }}</button>
                            @endif
                        </div>
                        <p id="avatar-ayuda" class="basis-full text-sm text-tinta-suave">{{ __('JPG, PNG or WebP, up to 2 MB. It is cropped square.') }}</p>
                        @if ($errorPerfil->has('avatar'))
                            <p id="avatar-error" class="basis-full text-sm font-semibold text-error">{{ $errorPerfil->first('avatar') }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="bio" class="text-[0.95rem] font-semibold">{{ __('Bio') }}</label>
                        <textarea id="bio" name="bio" rows="4" maxlength="{{ UpdateProfile::BIO_MAX }}" x-model="bio"
                                  aria-describedby="bio-contador @if ($errorPerfil->has('bio')) bio-error @endif"
                                  @if ($errorPerfil->has('bio')) aria-invalid="true" @endif
                                  class="resize-y rounded-lg border-2 border-linea-fuerte bg-white px-3.5 py-3 font-serif text-base leading-normal focus:border-tinta focus:outline-none {{ $errorPerfil->has('bio') ? 'border-error focus:border-error' : '' }}">{{ old('bio', $user->bio) }}</textarea>
                        <p id="bio-contador" class="self-end font-mono text-[0.8125rem] text-tinta-suave">
                            <span x-text="bio.length">{{ mb_strlen((string) old('bio', $user->bio)) }}</span> / {{ UpdateProfile::BIO_MAX }}
                        </p>
                        @if ($errorPerfil->has('bio'))
                            <p id="bio-error" class="text-sm font-semibold text-error">{{ $errorPerfil->first('bio') }}</p>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <button type="submit" class="h-12 rounded-lg bg-sello px-5.5 font-bold text-white hover:bg-sello-oscuro">{{ __('Save profile') }}</button>
                        @if (in_array($estado, [AccountController::PROFILE_UPDATED, AccountController::AVATAR_REMOVED], true))
                            <p role="status" class="font-display font-semibold text-ruta">✓ {{ $estado === AccountController::AVATAR_REMOVED ? __('Photo removed') : __('Profile saved') }}</p>
                        @endif
                    </div>
                </form>

                @if ($user->avatar)
                    <form id="form-quitar-avatar" method="POST" action="{{ route('account.avatar.destroy') }}" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </section>

            {{-- Vista previa: la ficha del perfil público a tamaño real; cambia mientras se escribe (#80) --}}
            <section aria-labelledby="vista-titulo"
                     class="flex flex-col gap-3.5 self-start lg:col-start-2 xl:sticky xl:top-6 xl:col-start-3 xl:row-span-3 xl:row-start-1">
                <div>
                    <h2 id="vista-titulo" class="text-xl font-extrabold">{{ __('How others see you') }}</h2>
                    <p class="text-sm text-tinta-suave">{{ __('It updates as you type.') }}</p>
                </div>
                <x-member-card :user="$user" live class="w-full max-w-100" />
            </section>
        </div>

        <section id="contrasena" aria-labelledby="contrasena-titulo" @if ($errorClave->any()) x-data x-init="$el.scrollIntoView()" @endif class="flex scroll-mt-6 flex-col gap-5 rounded-[14px] border border-linea bg-white p-5 lg:col-start-2 lg:p-8">
            <h2 id="contrasena-titulo" class="text-2xl font-extrabold">{{ __('Password') }}</h2>

            <form method="POST" action="{{ route('user-password.update') }}" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                @foreach ([
                    'current_password' => [__('Current password'), 'current-password'],
                    'password' => [__('New password'), 'new-password'],
                    'password_confirmation' => [__('Repeat the new password'), 'new-password'],
                ] as $campo => [$etiqueta, $autocompletar])
                    <div class="flex flex-col gap-1.5">
                        <label for="{{ $campo }}" class="text-[0.95rem] font-semibold">{{ $etiqueta }}</label>
                        <input id="{{ $campo }}" name="{{ $campo }}" type="password" required autocomplete="{{ $autocompletar }}"
                               @if ($campo === 'password') minlength="8" aria-describedby="password-ayuda @if ($errorClave->has('password')) password-error @endif" @endif
                               @if ($campo === 'current_password' && $errorClave->has('current_password')) aria-describedby="current_password-error" @endif
                               @if ($errorClave->has($campo)) aria-invalid="true" @endif
                               class="campo {{ $errorClave->has($campo) ? 'campo-error' : '' }}">
                        @if ($campo === 'password')
                            <p id="password-ayuda" class="text-sm text-tinta-suave">{{ __('At least 8 characters.') }}</p>
                        @endif
                        @if ($errorClave->has($campo))
                            <p id="{{ $campo }}-error" class="text-sm font-semibold text-error">{{ $errorClave->first($campo) }}</p>
                        @endif
                    </div>
                @endforeach

                <div class="flex flex-wrap items-center gap-4">
                    <button type="submit" class="h-12 rounded-lg bg-sello px-5.5 font-bold text-white hover:bg-sello-oscuro">{{ __('Change password') }}</button>
                    @if ($estado === Fortify::PASSWORD_UPDATED)
                        <p role="status" x-data x-init="$el.scrollIntoView({ block: 'center' })" class="font-display font-semibold text-ruta">✓ {{ __('Password changed') }}</p>
                    @endif
                </div>
            </form>
        </section>

        {{-- Mi zona (#18): el punto se redondea a ~250 m en el navegador (para enseñarlo) y en el servidor (para guardarlo) --}}
        <section id="zona" aria-labelledby="zona-titulo" @if ($errorZona->any() || in_array($estado, [ZoneController::UPDATED, ZoneController::REMOVED], true)) x-data x-init="$el.scrollIntoView()" @endif
                 class="flex scroll-mt-6 flex-col gap-4.5 rounded-[14px] border border-linea bg-white p-5 lg:col-start-2 lg:p-8">
            <div>
                <h2 id="zona-titulo" class="text-2xl font-extrabold">{{ __('My zone') }}</h2>
                <p class="mt-1 text-[0.95rem] text-tinta-suave">{{ __('Where you move around, to see books nearby without revealing your home.') }}</p>
            </div>

            <div x-data="zonaMapa(@js([
                    'lat' => $zona?->location->getLatitude(),
                    'lon' => $zona?->location->getLongitude(),
                    'radio' => ((int) old('radius_m', $zona->radius_m ?? UserZone::DEFAULT_RADIUS_M)) / 1000,
                    'source' => $zona?->source->value,
                    'centro' => [41.4036, 2.1744],
                    'zoom' => 12,
                    'teselas' => config('zbooks.map.tiles'),
                    'atribucion' => config('zbooks.map.attribution'),
                    'urlBusqueda' => route('account.zone.search'),
                    'idioma' => app()->getLocale(),
                    'textos' => [
                        'sinGeolocalizacion' => __('Your browser cannot share your location. Search an address or tap the map.'),
                        'geolocalizacionDenegada' => __('We could not get your location. Check the permission, or search an address or tap the map.'),
                        'sinResultados' => __('No places found. Try with the street and the city.'),
                        'errorBusqueda' => __('The search is not available right now. Tap the map instead.'),
                    ],
                 ]))" class="flex flex-col gap-4.5">

                <div x-show="! tienePunto" @style(['display: none' => (bool) $zona])>
                    <p class="font-display text-lg font-bold">{{ __('You have not chosen your zone yet') }}</p>
                    <p class="text-[0.95rem] text-tinta-suave">{{ __('With it you will see the books near you. Use your location, search an address or tap the map: your home or anywhere in your neighbourhood.') }}</p>
                </div>

                <div class="flex flex-wrap items-start gap-3">
                    <button type="button" @click="miUbicacion()" :disabled="localizando" :aria-busy="localizando"
                            class="inline-flex h-12.5 items-center gap-2 rounded-lg border-2 border-tinta px-4 text-[0.95rem] font-bold hover:bg-papel disabled:cursor-wait">
                        <svg x-show="! localizando" class="size-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
                        <x-radar x-show="localizando" x-cloak class="text-sello" />
                        <span x-text="localizando ? @js(__('Locating you…')) : @js(__('Use my location'))">{{ __('Use my location') }}</span>
                    </button>
                    {{-- Busca mientras se escribe (con una pausa de medio segundo); Intro no envía nada --}}
                    <form role="search" @submit.prevent class="relative min-w-0 flex-1 basis-60">
                        <label for="zona-buscar" class="sr-only">{{ __('Search an address') }}</label>
                        <div class="flex rounded-lg border-2 border-linea-fuerte bg-white focus-within:border-tinta">
                            <input id="zona-buscar" type="search" x-model="consulta" minlength="3" autocomplete="off"
                                   placeholder="{{ __('Search a street, square or neighbourhood') }}"
                                   class="h-11.5 min-w-0 flex-1 bg-transparent px-3.5 font-display focus:outline-none">
                            <span class="flex w-11 items-center justify-center text-sello">
                                <x-radar x-show="buscando" x-cloak />
                                <svg x-show="! buscando" class="size-4.5 text-tinta-suave" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m20 20-4.5-4.5"/></svg>
                            </span>
                        </div>
                        <ul x-show="resultados.length" x-cloak class="absolute inset-x-0 top-full z-[1000] mt-1 overflow-hidden rounded-lg border border-linea bg-white shadow-lg">
                            <template x-for="lugar in resultados" :key="lugar.name">
                                <li>
                                    <button type="button" @click="elegir(lugar.latitude, lugar.longitude, 'map')" x-text="lugar.name"
                                            class="block w-full px-3.5 py-2.5 text-left text-[0.95rem] leading-snug hover:bg-papel focus:bg-papel"></button>
                                </li>
                            </template>
                        </ul>
                    </form>
                </div>
                <p x-show="aviso" x-text="aviso" x-cloak role="status" class="text-sm font-semibold text-error"></p>
                <p x-show="source === 'geolocation' && tienePunto && ! localizando" x-cloak role="status" class="flex items-center gap-2 text-sm text-ruta">
                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 4.5-3.4 8.3-8 9-4.6-.7-8-4.5-8-9V6z"/></svg>
                    {{ __('Approximate location: we keep a point about 250 m from where you are.') }}
                </p>

                <div x-ref="mapa" class="h-75 overflow-hidden rounded-[10px] border border-linea lg:h-85" aria-label="{{ __('Map of your zone') }}"></div>

                <form method="POST" action="{{ route('account.zone.update') }}" class="flex flex-col gap-4.5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="latitude" :value="lat">
                    <input type="hidden" name="longitude" :value="lon">
                    <input type="hidden" name="source" :value="source">

                    <input type="hidden" name="radius_m" :value="radioMetros">

                    @php
                        $km = fn (int $metros): string => Number::format($metros / 1000, maxPrecision: 1, locale: app()->getLocale());
                        $radioInicial = (int) old('radius_m', $zona->radius_m ?? UserZone::DEFAULT_RADIUS_M);
                    @endphp
                    <div x-show="tienePunto" @style(['display: none' => ! $zona])>
                        <div class="flex items-baseline justify-between">
                            <label for="radio" class="text-[0.95rem] font-semibold">{{ __('Radius') }}</label>
                            <p class="font-mono text-[0.9375rem] font-medium text-sello"><span x-text="radioTexto">{{ $km($radioInicial) }}</span> km</p>
                        </div>
                        {{-- En kilómetros; el valor que se envía es radius_m --}}
                        <input id="radio" type="range" x-model.number="radio"
                               min="{{ UserZone::MIN_RADIUS_M / 1000 }}" max="{{ UserZone::MAX_RADIUS_M / 1000 }}" step="{{ UserZone::RADIUS_STEP_M / 1000 }}"
                               value="{{ $radioInicial / 1000 }}"
                               class="mt-1 w-full accent-sello">
                        <div class="flex justify-between font-mono text-xs text-tinta-suave" aria-hidden="true"><span>{{ $km(UserZone::MIN_RADIUS_M) }} km</span><span>{{ $km(UserZone::MAX_RADIUS_M) }} km</span></div>
                    </div>

                    @if ($errorZona->any())
                        <p class="text-sm font-semibold text-error">{{ $errorZona->first() }}</p>
                    @endif

                    <div class="flex gap-2.5 rounded-lg bg-papel-hondo px-3.5 py-3 text-sm leading-normal">
                        <svg class="mt-0.5 size-4.5 shrink-0 text-ruta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 4.5-3.4 8.3-8 9-4.6-.7-8-4.5-8-9V6z"/></svg>
                        <span>{{ __('We save an approximate point, about 250 m from the one you choose, never your address. Nobody else sees your zone.') }}</span>
                    </div>

                    <div x-show="tienePunto" @style(['display: none' => ! $zona]) class="flex flex-wrap items-center gap-4">
                        <button type="submit" class="h-12 rounded-lg bg-sello px-5.5 font-bold text-white hover:bg-sello-oscuro">{{ __('Save zone') }}</button>
                        @if ($zona)
                            <button type="submit" form="form-quitar-zona" class="font-display text-[0.95rem] font-semibold text-error underline">{{ __('Remove zone') }}</button>
                        @endif
                        @if (in_array($estado, [ZoneController::UPDATED, ZoneController::REMOVED], true))
                            <p role="status" class="font-display font-semibold text-ruta">✓ {{ $estado === ZoneController::UPDATED ? __('Zone saved') : __('Zone removed') }}</p>
                        @endif
                    </div>
                </form>
            </div>

            @if ($zona)
                <form id="form-quitar-zona" method="POST" action="{{ route('account.zone.destroy') }}" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @elseif ($estado === ZoneController::REMOVED)
                <p role="status" class="font-display font-semibold text-ruta">✓ {{ __('Zone removed') }}</p>
            @endif
        </section>
    </div>
</x-layouts::app>
