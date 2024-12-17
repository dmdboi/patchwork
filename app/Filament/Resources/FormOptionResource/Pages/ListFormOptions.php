<?php

namespace App\Filament\Resources\FormOptionResource\Pages;

use App\Filament\Resources\FormOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormOptions extends ListRecords
{
    protected static string $resource = FormOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
