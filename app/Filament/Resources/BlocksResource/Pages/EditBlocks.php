<?php

namespace App\Filament\Resources\BlocksResource\Pages;

use App\Filament\Resources\BlocksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlocks extends EditRecord
{
    protected static string $resource = BlocksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
