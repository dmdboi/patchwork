<?php

namespace App\Filament\Resources\CategoriesMetaResource\Pages;

use App\Filament\Resources\CategoriesMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoriesMetas extends ListRecords
{
    protected static string $resource = CategoriesMetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
