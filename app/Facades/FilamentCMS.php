<?php

namespace App\Facades;

use App\Services\FilamentCMSAuthors;
use App\Services\FilamentCMSThemes;
use App\Services\FilamentCMSTypes;
use Illuminate\Support\Facades\Facade;

/**
 * @method FilamentCMSAuthors authors()
 * @method FilamentCMSTypes types()
 * @method FilamentCMSThemes themes()
 */
class FilamentCMS extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'filament-cms';
    }
}
