<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Post $post): bool
    {
        if ($post->is_published) {
            return true;
        }

        // visitors cannot view unpublished items
        if ($user === null) {
            return false;
        }

        // admin overrides published status
        if ($user->hasAccess('view', 'posts')) {
            return true;
        }

        // authors can view their own unpublished posts
        return $user->id == $post->user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAccess('create', 'posts');
    }

    public function update(User $user, Post $post): bool
    {
        if ($user->hasAccess('edit', 'posts')) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->hasAccess('delete', 'posts')) {
            return true;
        }

        return false;
    }
}
