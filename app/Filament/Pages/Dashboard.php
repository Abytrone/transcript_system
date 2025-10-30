<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\SystemStatsWidget::class,
            \App\Filament\Widgets\MonthlyAnalyticsWidget::class,
            \App\Filament\Widgets\TranscriptStatusChartWidget::class,
            \App\Filament\Widgets\RecentTranscriptsTableWidget::class,
        ];
    }
}
