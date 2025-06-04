<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        // Necesario para que funcionen los componentes de Filament fuera del panel
        \Filament\Facades\Filament::serving(function () {
            \Filament\Facades\Filament::registerViteTheme('resources/css/filament/admin/theme.css');
        });
    }
}
