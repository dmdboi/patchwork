<?php

namespace App\Traits;

use App\Services\FilamentPostAuthors;

trait HasPosts
{
    public function posts()
    {
        return $this->morphMany(\App\Models\Post::class, 'authorable');
    }
}
