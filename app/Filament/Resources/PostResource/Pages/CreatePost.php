<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Events\PostCreated;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    public function afterCreate()
    {
        Event::dispatch(new PostCreated($this->getRecord()->toArray()));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'post';
        $data['slug'] = Str::slug($data['title']);

        return $data;
    }
}
