<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\TextInput;
use TomatoPHP\FilamentCms\Services\Contracts\Section;


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
        // Move this to App/Views
        themes()->register([
            Section::make('hero')
                ->label('Hero Section')
                ->view('hero')
                ->color('blue')
                ->form([
                    TextInput::make('title')
                        ->label('title'),
                    TextInput::make('description')
                        ->label('description'),
                    TextInput::make('url')
                        ->url()
                        ->label('url'),
                    TextInput::make('button')
                        ->label('button'),
                ])
        ]);
    }
}
