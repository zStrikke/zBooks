{{-- Verifica tu email (#10, #69). Diseño: docs/diseno/v0.2.0. El reenvío va al POST de Fortify --}}
<x-layouts::app :title="__('Check your email')">
    <div class="flex items-center justify-center px-4 py-10 sm:px-6 lg:py-16">
        <div class="flex w-full max-w-215 flex-wrap items-center gap-14">
            <div class="relative mx-auto flex h-48 w-48 shrink-0 -rotate-8 items-center justify-center lg:h-60 lg:w-60">
                <svg class="absolute inset-0 h-full w-full text-sello" viewBox="0 0 240 240" fill="none" stroke="currentColor" aria-hidden="true">
                    <circle cx="120" cy="120" r="104" stroke-width="5"/>
                    <circle cx="120" cy="120" r="88" stroke-width="2"/>
                    <path d="M20 112c20-12 40 12 60 0s40 12 60 0 40 12 60 0 20 12 30 6" stroke-width="3" stroke-linecap="round" opacity="0.35"/>
                    <path d="M20 136c20-12 40 12 60 0s40 12 60 0 40 12 60 0 20 12 30 6" stroke-width="3" stroke-linecap="round" opacity="0.35"/>
                </svg>
                <div class="relative flex flex-col items-center gap-1 text-sello" role="img" aria-label="{{ __('Postmark: sent, expires in :minutes min', ['minutes' => $expires]) }}">
                    <p class="font-display text-2xl font-extrabold">zBooks</p>
                    <p class="font-mono text-[0.95rem] font-semibold tracking-wider">{{ __('SENT') }}</p>
                    <p class="font-mono text-sm">{{ __('expires in :minutes min', ['minutes' => $expires]) }}</p>
                </div>
            </div>

            <section class="flex min-w-0 flex-1 basis-80 flex-col gap-6">
                <div class="flex flex-col gap-3">
                    <h1 class="text-4xl font-extrabold leading-none tracking-tight lg:text-5xl">{{ __('Check your email') }}</h1>
                    <p class="text-lg leading-normal lg:text-[1.1875rem]">{!! __('We have sent a link to :email. Press it to activate your account.', ['email' => '<b class="break-all">'.e($email).'</b>']) !!}</p>
                    <p class="text-tinta-suave">{{ __('If you don\'t see it in a few minutes, check your spam folder.') }}</p>
                </div>

                {{-- La cuenta atrás corre en el navegador; el límite real lo pone el servidor (VerificationEmailThrottle) --}}
                <form method="POST" action="{{ route('verification.send') }}"
                      x-data="{
                          wait: {{ $wait }},
                          get clock() { return Math.floor(this.wait / 60) + ':' + String(this.wait % 60).padStart(2, '0') },
                      }"
                      x-init="const timer = setInterval(() => { wait > 0 ? wait-- : clearInterval(timer) }, 1000)"
                      class="flex flex-wrap items-center gap-x-5 gap-y-3">
                    @csrf
                    <button type="submit" :disabled="wait > 0" @disabled($wait > 0)
                            class="h-12.5 rounded-lg border-2 border-sello px-6 font-bold text-sello hover:bg-sello hover:text-white disabled:border-linea-fuerte disabled:bg-transparent disabled:text-[#5b6573]">
                        {{ __('Resend the email') }}
                    </button>
                    <p class="text-[0.95rem] text-tinta-suave" aria-live="polite">
                        <span x-show="wait > 0" @if ($wait === 0) x-cloak @endif>{{ __('You can resend it in') }} <span x-text="clock" class="font-mono">{{ intdiv($wait, 60) }}:{{ str_pad((string) ($wait % 60), 2, '0', STR_PAD_LEFT) }}</span></span>
                    </p>
                </form>
                @if (session('resent'))
                    <p class="font-semibold text-ruta" role="status">{{ __('We have sent you a new link.') }}</p>
                @endif

                <div class="flex flex-col gap-1.5 rounded-xl bg-papel-hondo px-4.5 py-4">
                    <p class="font-display text-[0.95rem] font-bold">{{ __('In the meantime') }}</p>
                    <p class="text-[0.95rem] leading-snug">{{ __('You can explore zBooks. To register, release or catch books you need a verified email.') }}</p>
                </div>

                <a href="{{ route('home') }}" class="self-start font-display text-[0.95rem] font-bold text-sello hover:text-sello-oscuro">{{ __('Go to the home page') }}</a>
            </section>
        </div>
    </div>
</x-layouts::app>
