<?php

namespace App\Providers;

use App\Filament\PageBuilder\PageBuilderSection;
use Illuminate\Support\ServiceProvider;

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
            PageBuilderSection::make('hero')
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
