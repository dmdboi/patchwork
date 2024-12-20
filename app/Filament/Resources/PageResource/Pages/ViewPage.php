<?php

namespace App\Filament\Resources\PageResource\Pages;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Event;
use App\Events\PostDeleted;
use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Post;

class ViewPage extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

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
