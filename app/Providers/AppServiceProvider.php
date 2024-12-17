<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Facades\FilamentCMS;
use App\Services\Contracts\Section;

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
        // Move this to App/Views
        FilamentCMS::themes()->register([
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
