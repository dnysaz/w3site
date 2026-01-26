<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Baris penting 1

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
        // Baris penting 2: Memaksa semua URL menggunakan HTTPS di server production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}