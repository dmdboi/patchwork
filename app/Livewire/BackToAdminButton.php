<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class BackToAdminButton extends Component
{

    public $post;

    public $url;

    public function mount(?Post $post)
    {
        if (!$post) {
            $this->url = '/admin';
        }

        if ($post && $post->type === 'page') {
            $this->url = '/admin/pages/' . $post->id . '/edit';
        }

        if ($post && $post->type === 'post') {
            $this->url = '/admin/posts/' . $post->id . '/edit';
        }
    }

    public function render()
    {
        return view('livewire.back-to-admin-button', [
            'url' => $this->url
        ]);
    }
}
