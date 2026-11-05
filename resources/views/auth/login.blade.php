{{-- Login (#11). Diseño: docs/diseno/v0.2.0 (móvil y escritorio). El formulario va al POST /login de Fortify --}}
<x-layouts::app :title="__('Log in')">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-18 gap-y-10 px-4 py-10 sm:px-6 lg:py-16">
        <section class="flex w-full max-w-md flex-col gap-6 lg:flex-1">
            <div class="flex flex-col gap-2">
                <h1 class="text-4xl font-extrabold leading-none tracking-tight lg:text-5xl">{{ __('Welcome back') }}</h1>
                <p class="text-lg text-tinta-suave">{{ __('Log in to see where your books are.') }}</p>
            </div>

            @if ($errors->any())
                <div role="alert" class="flex items-start gap-3 rounded-lg border border-[#e3b9a2] bg-[#fbeee6] px-3.5 py-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#9a3b12]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
                    <p class="text-sm leading-snug text-[#6e2a0c]">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label for="login" class="text-[0.95rem] font-semibold">{{ __('Email or username') }}</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                           autocomplete="username" autocapitalize="off" spellcheck="false"
                           @error('login') aria-invalid="true" @enderror
                           class="campo @error('login') campo-error @enderror">
                </div>

                <div x-data="{ visible: false }" class="flex flex-col gap-1.5">
                    <div class="flex items-baseline justify-between gap-4">
                        <label for="password" class="text-[0.95rem] font-semibold">{{ __('Password') }}</label>
                        <a href="{{ route('password.request') }}" class="font-display text-sm font-bold text-sello hover:text-sello-oscuro">{{ __('Forgot it?') }}</a>
                    </div>
                    <div class="relative">
                        <input id="password" name="password" :type="visible ? 'text' : 'password'" type="password" required
                               autocomplete="current-password"
                               @error('password') aria-invalid="true" @enderror
                               class="campo w-full pr-12 @error('password') campo-error @enderror">
                        <button type="button" @click="visible = !visible" :aria-pressed="visible"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-tinta-suave hover:text-tinta">
                            <span class="sr-only" x-text="visible ? @js(__('Hide password')) : @js(__('Show password'))">{{ __('Show password') }}</span>
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path x-show="visible" x-cloak d="M4 4l16 16"/></svg>
                        </button>
                    </div>
                </div>

                <label for="remember" class="flex items-center gap-3 font-serif text-[0.95rem] font-normal">
                    <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember', true)) class="h-5.5 w-5.5 shrink-0 accent-sello">
                    <span>{{ __('Keep me logged in') }}</span>
                </label>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                    <button type="submit" class="h-13 w-full rounded-lg bg-sello px-7 text-[1.0625rem] font-bold text-white hover:bg-sello-oscuro sm:w-auto">
                        {{ __('Log in') }}
                    </button>
                    <p class="text-[0.95rem]">{{ __('First time here?') }}
                        <a href="{{ route('register') }}" class="font-display font-bold text-sello hover:text-sello-oscuro">{{ __('Create your account') }}</a>
                    </p>
                </div>
            </form>

            <div class="flex flex-col gap-1 rounded-xl bg-papel-hondo p-4 lg:hidden">
                <p class="font-display text-[0.95rem] font-bold">{{ __('Have you found a book?') }}</p>
                <p class="text-[0.95rem] leading-snug">{!! __('You don\'t need an account to see its journey: :link.', ['link' => '<a href="'.e(route('zbid.buscar')).'" class="text-sello underline">'.e(__('search the code on its label')).'</a>']) !!}</p>
            </div>
        </section>

        {{-- Escritorio: la etiqueta del libro, para quien llega con un libro en la mano --}}
        <section aria-label="{{ __('Search a book you found') }}" class="hidden flex-1 justify-center lg:flex">
            <div class="etiqueta flex w-full max-w-120 rotate-2 flex-col gap-4 rounded-b-[10px] px-10 pb-10 pt-11 shadow-[0_30px_60px_-32px_rgba(23,32,43,0.5)]">
                <p class="font-display text-[0.95rem] font-bold text-[#3d3416]">{{ __('No account and found a book?') }}</p>
                <h2 class="text-[2.125rem] font-extrabold leading-[1.05]">{{ __('You don\'t need to sign up to see its journey.') }}</h2>
                <p class="text-[#3d3416]">{{ __('Type the code on the label inside it.') }}</p>
                <form method="GET" action="{{ route('zbid.buscar') }}" role="search" class="flex flex-wrap gap-3">
                    <label for="zbid-login" class="w-full text-[0.95rem] font-semibold">{{ __('Book code') }}</label>
                    <input id="zbid-login" name="codigo" type="text" required placeholder="ZB-4K7Q-92XM" autocomplete="off" spellcheck="false"
                           class="h-12.5 min-w-0 flex-1 basis-55 rounded-lg border-2 border-tinta bg-white/90 px-3.5 font-mono text-lg uppercase tracking-wider focus:outline-none">
                    <button type="submit" class="h-12.5 rounded-lg bg-tinta px-5.5 font-bold text-papel hover:bg-tinta/85">{{ __('Search book') }}</button>
                </form>
            </div>
        </section>
    </div>
</x-layouts::app>
