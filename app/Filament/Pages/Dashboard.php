<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Models\User;
use Filament\Pages\Page;


class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getHeaderWidgetsColumns(): array|int|string
    {
        return 3;
    }

    public function getHeaderWidgets(): array
    {
        return [
            //
            StatsOverview::class,
        ];
    }

}
