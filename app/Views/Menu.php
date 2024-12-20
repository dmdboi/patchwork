<?php

namespace App\Filament\Views;

use App\Models\Menu as MenuModel;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Menu extends Component
{
    public Collection $menuItems;

    public function __construct(
        public string $menu,
        public ?string $view = 'menu',
    ) {
        $menu = MenuModel::where('key', $menu)->first();
        $this->menuItems = collect($menu->menuItems ?? []);
    }

    public function render()
    {
        if (view()->exists($this->view)) {
            return view($this->view);
        } else {
            return view('components/menu');
        }
    }
}
