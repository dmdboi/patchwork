<?php

namespace App\Filament\Resources\CategoriesMetaResource\Pages;

use App\Filament\Resources\CategoriesMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoriesMeta extends EditRecord
{
    protected static string $resource = CategoriesMetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
