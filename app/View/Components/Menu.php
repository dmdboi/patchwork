<?php

namespace App\View\Components;

use App\Models\Menu as MenuModel;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Menu extends Component
{
    public Collection $menuItems;

    public ?string $component = '';

    public function __construct(string $menu, ?string $component = null) {

        if(isset($component)) {
            $this->component = $component;
        }

        $menu = MenuModel::where('key', $menu)->first();
        $this->menuItems = collect($menu->menuItems ?? []);
    }

    public function render()
    {
        if (view()->exists($this->component)) {
            return view($this->component);
        } else {
            return view('theme/components/menu');
        }
    }
}
