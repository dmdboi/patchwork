<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Card::make('Users', User::count())
            ->icon('heroicon-o-user-group'),
            Card::make('Posts', Post::query()->where('type', 'post')->count())
            ->icon('heroicon-o-document-text'),
            Card::make('Pages', Post::query()->where('type', 'page')->count())
            ->icon('heroicon-o-globe-alt'),
        ];
    }
}
