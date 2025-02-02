<?php

namespace App\Services;

use App\Services\Contracts\CmsType;

class FilamentCMSTypes
{
    public static array $cmsTypes = [];

    public static function register(CmsType|array $cmsType)
    {
        if (is_array($cmsType)) {
            foreach ($cmsType as $type) {
                self::register($type);
            }

            return;
        }

        self::$cmsTypes[] = $cmsType;
    }

    public static function getOptions()
    {
        return collect(self::$cmsTypes);
    }
}
