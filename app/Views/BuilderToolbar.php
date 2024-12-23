<?php

namespace App\Views;

use App\Models\Post;
use Illuminate\View\Component;
use Illuminate\View\View;

class BuilderToolbar extends Component
{
    public function __construct(
        public Post $page,
        public bool $allowLayout = false
    ) {
        //
    }

    public function render(): View
    {
        return view('admin.builder-toolbar');
    }
}
