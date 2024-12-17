<?php

namespace TomatoPHP\FilamentCms\Views;

use Illuminate\View\Component;
use Illuminate\View\View;
use TomatoPHP\FilamentCms\Models\Post;

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
