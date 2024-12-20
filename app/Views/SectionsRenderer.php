<?php

namespace App\Views;

use Illuminate\View\Component;
use Illuminate\View\View;

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
