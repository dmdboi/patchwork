<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Form;
use App\Services\Contracts\Section;

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
