<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SiswaPerKelasChart;
use App\Filament\Widgets\OverviewStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            OverviewStatsWidget::class,
            SiswaPerKelasChart::class
        ];
    }
}
