<?php

namespace App\Services;

use App\Services\Contracts\CmsAuthor;

class FilamentCMSAuthors
{
    public static array $authorTypes = [];

    public static function register(CmsAuthor|array $author)
    {
        if (is_array($author)) {
            foreach ($author as $type) {
                self::register($type);
            }

            return;
        }
        self::$authorTypes[] = $author;
    }

    public static function getOptions()
    {
        return collect(self::$authorTypes);
    }
}
