<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        // Daftarkan path anonymous Blade component agar x-layouts.app dan x-layouts.guest bisa digunakan
        Blade::anonymousComponentPath(resource_path('views/containner/layouts'), 'layouts');
    }
}
