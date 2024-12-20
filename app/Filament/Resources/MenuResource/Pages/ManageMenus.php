<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ManageMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    public function getTitle(): string
    {
        return 'List Menus';
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Menu'),
        ];
    }
}
