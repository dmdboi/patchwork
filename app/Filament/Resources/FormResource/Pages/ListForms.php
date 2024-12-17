<?php

namespace App\Filament\Resources\FormResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\FormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForms extends ManageRecords
{
    use ManageRecords\Concerns\Translatable;

    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }
}
