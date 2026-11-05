{{-- He olvidado mi contraseña (#11). Sin maqueta propia: sigue el estilo del login --}}
<x-layouts::app :title="__('Forgot your password?')">
    <div class="mx-auto flex max-w-md flex-col gap-6 px-4 py-10 sm:px-6 lg:py-16">
        <div class="flex flex-col gap-2">
            <h1 class="text-4xl font-extrabold leading-none tracking-tight">{{ __('Forgot your password?') }}</h1>
            <p class="text-lg text-tinta-suave">{{ __('Write your email and we will send you a link to choose a new one.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[0.95rem] font-semibold">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       placeholder="{{ __('you@email.com') }}"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                       class="campo @error('email') campo-error @enderror">
                @error('email') <p id="email-error" class="text-sm font-semibold text-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="h-13 rounded-lg bg-sello px-7 text-[1.0625rem] font-bold text-white hover:bg-sello-oscuro sm:self-start">
                {{ __('Send me the link') }}
            </button>
        </form>

        <a href="{{ route('login') }}" class="self-start font-display text-[0.95rem] font-bold text-sello hover:text-sello-oscuro">{{ __('Back to log in') }}</a>
    </div>
</x-layouts::app>
