<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Events\PostDeleted;
use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Event;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('preview-button')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->action(fn () => redirect()->to('/preview/blog/'.$this->getRecord()->slug)),
            Actions\DeleteAction::make()->before(fn (Post $record) => Event::dispatch(new PostDeleted($record->toArray()))),
        ];
    }
}
