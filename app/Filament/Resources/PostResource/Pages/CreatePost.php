<?php

namespace App\Filament\Resources\PostResource\Pages;

use Illuminate\Support\Facades\Event;
use App\Events\PostCreated;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\GitHubMetaGetterJob;
use App\Jobs\YoutubeMetaGetterJob;
use App\Models\Post;

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
}
