<?php

namespace App\Services;

use App\Services\Contracts\Section;
use Illuminate\Support\Collection;

class FilamentCMSThemes
{
    private static array $sections = [];

    public static function register()
    {

        $themes = config('filament-cms.themes');

        foreach ($themes as $theme) {

            $path = app_path('Views/Themes/'.$theme.'.php');

            if (file_exists($path)) {
                self::addSections(require $path);
            }
        }
    }

    public static function addSections(Section|array $section)
    {
        if (is_array($section)) {
            foreach ($section as $item) {
                self::addSections($item);
            }

            return;
        } else {
            self::$sections[] = $section;
        }
    }

    public static function getSections(): Collection
    {
        return collect(self::$sections);
    }
}
