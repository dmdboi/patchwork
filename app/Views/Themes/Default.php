<?php

use App\Models\Collection;
use App\Services\Contracts\Section;
use Filament\Forms\Components\Select;

return [
    Section::make('collection-menu')
        ->label('Collection Menu')
        ->view('Default.collection-menu')
        ->form([
            Select::make('collection')
                ->label('Collection')
                ->placeholder('Select a collection')
                ->options(
                    collect(Collection::all())
                        ->mapWithKeys(fn(Collection $collection) => [$collection->id => $collection->name])
                        ->toArray(),
                )
                ->required(),
        ]),
];