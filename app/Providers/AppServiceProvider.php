<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
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
    public function boot(): void
    {
        // Fechas en español en toda la app (ej. "martes, 21 de mayo").
        Carbon::setLocale('es');

        // En producción detrás de un proxy con TLS (Railway), forzar HTTPS en
        // las URLs generadas para que los assets no queden como http:// y el
        // navegador los bloquee por contenido mixto.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
