<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Models\Post;
use App\Events\PostDeleted;
use App\Filament\Resources\PageResource;

use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

use Illuminate\Support\Facades\Event;

class ViewPage extends ViewRecord
{

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('preview-button')
            ->label('Preview')
            ->icon('heroicon-o-eye')
            ->color('primary')
            ->action(fn() => redirect()->to('/preview/' . $this->getRecord()->slug)),
            Actions\DeleteAction::make()->before(fn(Post $record) => Event::dispatch(new PostDeleted($record->toArray())))
        ];
    }
}
