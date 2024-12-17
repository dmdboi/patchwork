<?php

namespace App\Filament\Resources\PostMetaResource\Pages;

use App\Filament\Resources\PostMetaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostMetas extends ListRecords
{
    protected static string $resource = PostMetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
