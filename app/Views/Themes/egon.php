<?php

use App\Services\Contracts\Section;
use Filament\Forms\Components\TextInput;

return [
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
        ]),
];
