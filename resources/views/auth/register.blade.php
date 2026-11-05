{{-- Registro (#10, #69). Diseño: docs/diseno/v0.2.0. El formulario va al POST /register de Fortify --}}
<x-layouts::app :title="__('Create your account')">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-18 gap-y-10 px-4 py-10 sm:px-6 lg:py-16">
        <section class="flex w-full max-w-md flex-col gap-6 lg:flex-1">
            <div class="flex flex-col gap-2">
                <h1 class="text-4xl font-extrabold leading-none tracking-tight lg:text-5xl">{{ __('Create your account') }}</h1>
                <p class="text-lg text-tinta-suave">{{ __('Register your books, set them free and follow every stop of their journey.') }}</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" x-data="{ enviando: false }" @submit="enviando = true" class="flex flex-col gap-5" novalidate>
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label for="username" class="text-[0.95rem] font-semibold">{{ __('Username') }}</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username"
                           required minlength="{{ \App\Rules\Username::MIN }}" maxlength="{{ \App\Rules\Username::MAX }}" pattern="[A-Za-z0-9_]+" autocapitalize="off" spellcheck="false"
                           aria-describedby="username-ayuda @error('username') username-error @enderror"
                           @error('username') aria-invalid="true" @enderror
                           class="campo @error('username') campo-error @enderror">
                    <p id="username-ayuda" class="text-sm text-tinta-suave">{{ __('This is how others will see you. :min to :max letters, numbers or _.', ['min' => \App\Rules\Username::MIN, 'max' => \App\Rules\Username::MAX]) }}</p>
                    @error('username') <p id="username-error" class="text-sm font-semibold text-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap gap-5">
                    <div class="flex min-w-50 flex-1 flex-col gap-1.5">
                        <label for="email" class="text-[0.95rem] font-semibold">{{ __('Email') }}</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required
                               placeholder="{{ __('you@email.com') }}"
                               aria-describedby="email-ayuda @error('email') email-error @enderror"
                               @error('email') aria-invalid="true" @enderror
                               class="campo @error('email') campo-error @enderror">
                        <p id="email-ayuda" class="text-sm text-tinta-suave">{{ __('Never shown to anyone.') }}</p>
                        @error('email') <p id="email-error" class="text-sm font-semibold text-error">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ visible: false }" class="flex min-w-50 flex-1 flex-col gap-1.5">
                        <label for="password" class="text-[0.95rem] font-semibold">{{ __('Password') }}</label>
                        <div class="relative">
                            <input id="password" name="password" :type="visible ? 'text' : 'password'" type="password"
                                   autocomplete="new-password" required minlength="8"
                                   aria-describedby="password-ayuda @error('password') password-error @enderror"
                                   @error('password') aria-invalid="true" @enderror
                                   class="campo w-full pr-12 @error('password') campo-error @enderror">
                            <button type="button" @click="visible = !visible" :aria-pressed="visible"
                                    class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-tinta-suave hover:text-tinta">
                                <span class="sr-only" x-text="visible ? @js(__('Hide password')) : @js(__('Show password'))">{{ __('Show password') }}</span>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path x-show="visible" x-cloak d="M4 4l16 16"/></svg>
                            </button>
                        </div>
                        <p id="password-ayuda" class="text-sm text-tinta-suave">{{ __('At least 8 characters.') }}</p>
                        @error('password') <p id="password-error" class="text-sm font-semibold text-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="privacy_policy" class="flex items-start gap-3 font-serif text-[0.95rem] font-normal leading-snug">
                        <input id="privacy_policy" name="privacy_policy" type="checkbox" value="1" @checked(old('privacy_policy')) required
                               @error('privacy_policy') aria-invalid="true" aria-describedby="privacy-error" @enderror
                               class="mt-0.5 h-5.5 w-5.5 shrink-0 accent-sello">
                        <span>{!! __('I have read and accept the :link.', ['link' => '<a href="'.e(route('legal.privacy')).'" target="_blank" class="text-sello underline hover:text-sello-oscuro">'.e(__('privacy policy')).'</a>']) !!}</span>
                    </label>
                    @error('privacy_policy') <p id="privacy-error" class="text-sm font-semibold text-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                    <button type="submit" :disabled="enviando"
                            class="h-13 w-full rounded-lg bg-sello px-7 text-[1.0625rem] font-bold text-white hover:bg-sello-oscuro disabled:opacity-70 sm:w-auto">
                        <span x-show="!enviando">{{ __('Create account') }}</span>
                        <span x-show="enviando" x-cloak>{{ __('Creating your account…') }}</span>
                    </button>
                    <p class="text-[0.95rem]">{{ __('Already have an account?') }}
                        <a href="{{ route('login') }}" class="font-display font-bold text-sello hover:text-sello-oscuro">{{ __('Log in') }}</a>
                    </p>
                </div>
            </form>

            <div class="flex items-start gap-3 rounded-xl bg-papel-hondo p-4 lg:hidden">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-ruta" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                <p class="text-[0.95rem] leading-snug">{{ __('In the books\' journals you will appear as "a user". Only your friends will see your name.') }}</p>
            </div>
        </section>

        {{-- Escritorio: la tarjeta de viaje explica qué pasará con sus libros --}}
        <section aria-label="{{ __('What a book\'s journey looks like') }}" class="hidden flex-1 flex-col items-center gap-5 lg:flex">
            <div class="flex w-full max-w-136 -rotate-[1.5deg] flex-col gap-4 rounded-md bg-[#fbfaf3] px-9 pb-8 pt-9 shadow-[0_30px_60px_-32px_rgba(23,32,43,0.45)]">
                <div class="flex items-baseline justify-between gap-4 border-b-2 border-tinta pb-3">
                    <p class="font-display text-xl font-extrabold">{{ __('Travel card') }}</p>
                    <p class="font-mono text-[0.95rem] font-semibold tracking-wider">ZB-4K7Q-92XM</p>
                </div>
                <div class="flex flex-col">
                    @foreach ([
                        ['date' => __('3 Sep'), 'place' => 'Gràcia, Barcelona', 'stamp' => __('REGISTERED'), 'color' => 'border-sello text-sello', 'tilt' => '-rotate-3'],
                        ['date' => __('5 Sep · afternoon'), 'place' => __('Plaça del Sol · by a user'), 'stamp' => __('RELEASED'), 'color' => 'border-ruta text-ruta', 'tilt' => 'rotate-2'],
                        ['date' => __('19 Sep · night'), 'place' => __('Estació de Sants · by a guest'), 'stamp' => __('CAUGHT'), 'color' => 'border-sello text-sello', 'tilt' => '-rotate-2'],
                    ] as $parada)
                        <div class="grid grid-cols-[8.5rem_minmax(0,1fr)_8.25rem] items-center gap-3 border-b border-linea py-3">
                            <p class="font-mono text-sm">{{ $parada['date'] }}</p>
                            <p class="text-[0.95rem]">{{ $parada['place'] }}</p>
                            <p class="justify-self-start rounded border-2 px-2.5 py-0.5 font-mono text-[0.8rem] font-semibold {{ $parada['color'] }} {{ $parada['tilt'] }}">{{ $parada['stamp'] }}</p>
                        </div>
                    @endforeach
                    <div class="grid grid-cols-[8.5rem_minmax(0,1fr)_8.25rem] items-center gap-3 py-3">
                        <p class="font-mono text-sm text-tinta-suave/70">—</p>
                        <p class="text-[0.95rem] italic text-tinta-suave">{{ __('Where will the next stop be?') }}</p>
                        <p class="justify-self-start rounded border-2 border-dashed border-[#b9c1b4] px-2.5 py-0.5 font-mono text-[0.8rem] font-semibold text-tinta-suave">{{ __('YOUR TURN') }}</p>
                    </div>
                </div>
            </div>
            <p class="max-w-md text-center text-[0.95rem] leading-normal text-tinta-suave">{{ __('Each book keeps its journey with the date and time of day. Who left it, only their friends can see.') }}</p>
        </section>
    </div>
</x-layouts::app>
