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

        // Fetch theme name from config
        $theme = config('filament-cms.theme');

        // Get the theme's sections from Views/Themes/{theme}.php
        $sections = require app_path("Views/Themes/{$theme}.php");

        // Move this to App/Views
        FilamentCMS::themes()->register($sections);
    }
}
