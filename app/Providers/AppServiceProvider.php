<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

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
        // Solución alternativa para el error de Vite manifest
        if (app()->environment('local')) {
            // Desactivar la necesidad del archivo manifest.json
            Vite::useBuildDirectory(''); // Usa una ruta vacía para que no busque el manifest
            
            // Usar CSS y JS directamente en lugar de Vite
            Vite::macro('reactRefresh', fn () => '');
            Vite::macro('__invoke', fn () => $this);
            Vite::macro('__call', function ($method, $args) {
                return $this;
            });
        }
    }
}
