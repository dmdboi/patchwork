<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('editor-button')
            ->label('Editor')
            ->icon('heroicon-o-pencil')
            ->action(fn () => redirect()->to('/admin/editor/'.$this->getRecord()->slug)),
        Actions\Action::make('preview-button')
            ->label('Preview')
            ->icon('heroicon-o-eye')
            ->color('primary')
            ->action(fn () => redirect()->to('/preview/'.$this->getRecord()->slug)),
            Actions\DeleteAction::make(),
        ];
    }
}
