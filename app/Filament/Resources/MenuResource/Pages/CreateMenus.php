<?php

namespace App\Filament\Resources\MenuResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MenuResource;

class CreateMenus extends CreateRecord
{
    protected static string $resource = MenuResource::class;

    #[Reactive]
    public ?string $local = "en";
    public function setLocal($local){
        $this->local = $local;
        $this->activeLocale = $local;
    }


    public function getTitle(): string
    {
        return 'Create Menu';
    }
}
