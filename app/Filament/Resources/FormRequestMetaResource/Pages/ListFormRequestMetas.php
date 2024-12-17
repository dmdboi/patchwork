<?php

namespace App\Filament\Resources\FormRequestMetaResource\Pages;

use App\Filament\Resources\FormRequestMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFormRequestMetas extends ListRecords
{
    protected static string $resource = FormRequestMetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
