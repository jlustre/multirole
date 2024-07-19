<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
            ->description('Users that have joined')
            ->descriptionIcon('heroicon-o-users', IconPosition::Before),
            Stat::make('Total Posts', Post::count()),
            Stat::make('Total Post Categories', Category::count()),
        ];
    }
}
