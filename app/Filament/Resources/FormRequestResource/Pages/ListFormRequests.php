<?php

namespace App\Filament\Resources\FormRequestResource\Pages;

use App\Filament\Resources\FormRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormRequests extends ListRecords
{
    protected static string $resource = FormRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
