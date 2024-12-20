<?php

namespace App\Filament\Resources\PostResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use App\Events\PostCreated;
use App\Events\PostUpdated;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Jobs\GitHubMetaGetterJob;
use App\Jobs\YoutubeMetaGetterJob;

class EditPost extends EditRecord
{

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('editor-button')
                ->label('Editor')
                ->icon('heroicon-o-pencil')
                ->action(fn() => redirect()->to('/admin/editor/' . $this->getRecord()->slug)),
            Actions\Action::make('preview-button')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->action(fn() => redirect()->to('/preview/' . $this->getRecord()->slug)),
            Actions\DeleteAction::make()
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }


    public function afterSave()
    {
        Event::dispatch(new PostUpdated($this->getRecord()->toArray()));
    }
}
