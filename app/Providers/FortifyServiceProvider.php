<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\VerifyEmailResponse;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Laravel\Fortify\Fortify;

/**
 * Fortify sin vistas (ADR-0012): pone las acciones y las rutas POST y de
 * verificación; las pantallas son componentes Livewire propios (routes/web.php).
 */
class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(VerifyEmailResponseContract::class, VerifyEmailResponse::class);
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
    }
}
