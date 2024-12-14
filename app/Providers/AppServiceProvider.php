<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use TomatoPHP\FilamentCms\Services\Contracts\Section;
use TomatoPHP\FilamentCms\Facades\FilamentCMS;
use Filament\Forms\Components\TextInput;


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
        //
        FilamentCMS::themes()->register([
            Section::make('hero')
                ->label('Hero Section')
                ->view('sections.pages.hero')
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
