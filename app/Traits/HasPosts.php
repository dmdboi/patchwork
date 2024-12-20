<?php

namespace App\Traits;

trait HasPosts
{
    public function posts()
    {
        return $this->morphMany(\App\Models\Post::class, 'authorable');
    }
}
