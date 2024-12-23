<?php

use App\Models\Collection;
use App\Models\Menu;
use App\Services\Contracts\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;


return [
    Section::make('collection-menu')
        ->label('Collection Menu')
        ->view('components.collection-menu')
        ->form([
            Select::make('collection')
                ->label('Collection')
                ->placeholder('Select a collection')
                ->options(
                    fn() => Collection::query()
                        ->get()
                        ->mapWithKeys(fn(Collection $collection) => [$collection->id => $collection->name])
                        ->toArray()
                )
                ->required(),
        ]),
    Section::make('navigation')
        ->label('Navigation Section')
        ->view('sections.navigation')
        ->form([
            Select::make('menu')
                ->label('Menu')
                ->placeholder('Select a menu')
                ->options(
                    fn() => Menu::query()
                        ->get()
                        ->mapWithKeys(fn(Menu $menu) => [$menu->key => $menu->title])
                        ->toArray() ?? []
                )->required(),
        ]),
    Section::make('header')
        ->label('Header')
        ->view('sections.header')
        ->form([
            TextInput::make('title')->label('title'),
        ]),
    Section::make('content')
        ->label('Content')
        ->view('sections.content')
        ->form([
            TextInput::make('title')->label('title'),
            Textarea::make('content')->label('content'),
        ]),
    Section::make('posts')
        ->label('Posts')
        ->view('sections.posts')
        ->form([
            TextInput::make('title')->label('title'),
            Select::make('collection')
                ->label('Collection')
                ->placeholder('Select a collection')
                ->options(
                    fn() => Collection::query()
                        ->get()
                        ->mapWithKeys(fn(Collection $collection) => [$collection->id => $collection->name])
                        ->toArray()
                )
                ->required(),
        ]),
    Section::make('footer')
        ->label('Footer')
        ->view('sections.footer')
        ->form([
            TextInput::make('title')->label('title'),
        ]),
];
