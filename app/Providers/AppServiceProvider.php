<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Regla única de contraseña para toda la app.
        // Cualquier validate() que use Password::defaults() respeta este estándar.
        // Cambiar el mínimo o añadir reglas (letras/números/símbolos) se hace UNA sola vez, aquí.
        Password::defaults(function () {
            return Password::min(8);
        });
    }
}