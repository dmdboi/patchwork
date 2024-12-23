<?php

namespace App\Providers;

use App\Facades\FilamentCMS;
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
        // Register themes
        FilamentCMS::themes()->register();
    }
}
