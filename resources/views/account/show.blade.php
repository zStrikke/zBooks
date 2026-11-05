{{-- Mi cuenta (#17). Diseño: docs/diseno/v0.2.0 (Mi cuenta). Mi zona llega con la #18 --}}
@php
    use App\Actions\Users\UpdateProfile;
    use App\Http\Controllers\AccountController;
    use Laravel\Fortify\Fortify;

    $estado = session('status');
    $errorPerfil = $errors->updateProfile;
    $errorClave = $errors->updatePassword;
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
                <span class="shrink-0 rounded-full border-[1.5px] border-dashed border-linea-fuerte px-3.5 py-2 text-[#8a93a0] lg:rounded-none lg:border-0 lg:px-4">
                    {{ __('My zone') }} <span class="font-mono text-xs font-medium">· {{ __('soon') }}</span>
                </span>
            </nav>
            <a href="{{ route('profile.show', $user->username) }}" class="hidden font-display text-[0.95rem] font-semibold text-sello underline hover:text-sello-oscuro lg:block">{{ __('See my public profile') }}</a>
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
                        {{ __('What anyone sees on') }} <a href="{{ route('profile.show', $user->username) }}" class="text-sello underline hover:text-sello-oscuro">{{ __('your public profile') }}</a>.
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

            {{-- Vista previa: la ficha del perfil público, en móvil o en escritorio --}}
            <section aria-labelledby="vista-titulo" x-data="{ pestana: 'movil' }"
                     class="flex flex-col gap-3.5 self-start rounded-[14px] border border-linea bg-white p-5 lg:col-start-2 xl:sticky xl:top-6 xl:col-start-3 xl:row-span-3 xl:row-start-1">
                <div>
                    <h2 id="vista-titulo" class="text-xl font-extrabold">{{ __('How others see you') }}</h2>
                    <p class="text-sm text-tinta-suave">{{ __('It updates as you type.') }}</p>
                </div>

                <div role="tablist" aria-label="{{ __('Screen') }}" class="flex gap-1 rounded-lg bg-papel-hondo p-1"
                     @keydown.right.prevent="pestana = 'escritorio'; $nextTick(() => $refs.escritorio.focus())"
                     @keydown.left.prevent="pestana = 'movil'; $nextTick(() => $refs.movil.focus())">
                    @foreach (['movil' => __('Mobile'), 'escritorio' => __('Desktop')] as $clave => $texto)
                        <button type="button" role="tab" id="pestana-{{ $clave }}" x-ref="{{ $clave }}" aria-controls="panel-{{ $clave }}"
                                @click="pestana = '{{ $clave }}'"
                                :aria-selected="pestana === '{{ $clave }}'" aria-selected="{{ $clave === 'movil' ? 'true' : 'false' }}"
                                :tabindex="pestana === '{{ $clave }}' ? 0 : -1" tabindex="{{ $clave === 'movil' ? 0 : -1 }}"
                                :class="pestana === '{{ $clave }}' ? 'bg-white text-tinta shadow-sm' : 'text-tinta-suave'"
                                class="h-9 flex-1 rounded-md text-sm font-bold">
                            {{ $texto }}
                        </button>
                    @endforeach
                </div>

                {{-- Cada marco pinta la pantalla a tamaño real y la reduce con zoom --}}
                <div id="panel-movil" role="tabpanel" aria-labelledby="pestana-movil" x-show="pestana === 'movil'"
                     class="mx-auto w-fit rounded-[28px] border-2 border-tinta bg-papel px-2 pb-6 pt-5">
                    <div class="w-90 px-5 [zoom:0.7]">
                        <x-member-card :user="$user" size="movil" live />
                    </div>
                </div>

                <div id="panel-escritorio" role="tabpanel" aria-labelledby="pestana-escritorio" x-show="pestana === 'escritorio'" x-cloak
                     class="overflow-hidden rounded-[10px] border-2 border-tinta bg-papel">
                    <div aria-hidden="true" class="flex h-4 items-center gap-1 border-b border-linea bg-papel-hondo pl-2">
                        <i class="size-1.5 rounded-full bg-linea-fuerte"></i><i class="size-1.5 rounded-full bg-linea-fuerte"></i><i class="size-1.5 rounded-full bg-linea-fuerte"></i>
                    </div>
                    <div class="flex w-160 gap-8 p-8 [zoom:0.48]">
                        <x-member-card :user="$user" size="escritorio" live class="w-85 shrink-0" />
                        <div aria-hidden="true" class="flex flex-1 flex-col gap-4">
                            <div class="h-6 w-24 rounded bg-linea"></div>
                            <div class="flex-1 rounded-[14px] border-2 border-dashed border-linea-fuerte"></div>
                        </div>
                    </div>
                </div>
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

        <section aria-labelledby="zona-titulo" class="flex flex-col gap-2 rounded-[14px] border-2 border-dashed border-linea-fuerte p-5 lg:col-start-2 lg:p-7">
            <h2 id="zona-titulo" class="text-[1.375rem] font-extrabold text-tinta-suave">{{ __('My zone') }}</h2>
            <p class="text-[0.95rem] leading-normal text-tinta-suave">{{ __('Where you move around, to see books nearby without revealing your home. Coming in the next delivery.') }}</p>
        </section>
    </div>
</x-layouts::app>
