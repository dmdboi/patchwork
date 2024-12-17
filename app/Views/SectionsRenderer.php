<?php

namespace App\Views;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Models\Post;

class SectionsRenderer extends Component
{

    public function __construct(
        
    ) {
        //
    }

    public function render(): View
    {
        return view('filament-cms::components.sections-renderer');
    }
}
