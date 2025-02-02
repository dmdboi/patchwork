<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use Filament\Actions;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'admin.dashboard';

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

    public function getHeaderActions(): array
    {
        return [
            //
            Actions\Action::make('Create Post')
                ->icon('heroicon-o-plus')
                ->url('/admin/posts/create'),
        ];
    }
}
