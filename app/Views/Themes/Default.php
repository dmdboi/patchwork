<?php

use App\Models\Collection;
use App\Services\Contracts\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

return [
    Section::make('collection-menu')
        ->label('Collection Menu')
        ->view('components.collection-menu')
        ->form([
            Select::make('collection')
                ->label('Collection')
                ->placeholder('Select a collection')
                ->options([]
                    // collect(Collection::all())
                    //     ->mapWithKeys(fn (Collection $collection) => [$collection->id => $collection->name])
                    //     ->toArray(),
                )
                ->required(),
        ]),
    Section::make('hero')
        ->label('Hero Section')
        ->view('sections.hero')
        ->form([
            TextInput::make('title')->label('title'),
            TextInput::make('description')->label('description'),
            TextInput::make('url')->url()->label('url'),
            TextInput::make('button')->label('button'),
        ]),
];
