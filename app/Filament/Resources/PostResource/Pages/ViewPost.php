<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Event;
use App\Events\PostDeleted;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Post;

class ViewPost extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()->before(fn(Post $record) => Event::dispatch(new PostDeleted($record->toArray())))
        ];
    }
}