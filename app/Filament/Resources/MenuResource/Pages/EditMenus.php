<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Resources\Pages\EditRecord;

class EditMenus extends EditRecord
{
    protected static string $resource = MenuResource::class;

    #[Reactive]
    public ?string $local = 'en';

    public function setLocal($local)
    {
        $this->local = $local;
        $this->activeLocale = $local;
    }

    public function getTitle(): string
    {
        return 'Edit Menu';
    }
}
