{{-- Nueva contraseña desde el enlace del email (#11). Va al POST /reset-password de Fortify --}}
<x-layouts::app :title="__('Choose a new password')">
    <div class="mx-auto flex max-w-md flex-col gap-6 px-4 py-10 sm:px-6 lg:py-16">
        <div class="flex flex-col gap-2">
            <h1 class="text-4xl font-extrabold leading-none tracking-tight">{{ __('Choose a new password') }}</h1>
            <p class="text-lg text-tinta-suave">{{ __('After saving it, log in with it.') }}</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[0.95rem] font-semibold">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email', request()->query('email')) }}" required autocomplete="email"
                       @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                       class="campo @error('email') campo-error @enderror">
                @error('email') <p id="email-error" class="text-sm font-semibold text-error">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ visible: false }" class="flex flex-col gap-1.5">
                <label for="password" class="text-[0.95rem] font-semibold">{{ __('New password') }}</label>
                <div class="relative">
                    <input id="password" name="password" :type="visible ? 'text' : 'password'" type="password" required autofocus
                           minlength="8" autocomplete="new-password"
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

            <button type="submit" class="h-13 rounded-lg bg-sello px-7 text-[1.0625rem] font-bold text-white hover:bg-sello-oscuro sm:self-start">
                {{ __('Save the new password') }}
            </button>
        </form>
    </div>
</x-layouts::app>
