<?php

namespace App\Services;

use App\Services\Contracts\Section;
use Illuminate\Support\Collection;

class FilamentCMSThemes
{
    private static array $sections = [];

    public static function register(Section|array $section)
    {
        if (is_array($section)) {
            foreach ($section as $item) {
                self::register($item);
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
