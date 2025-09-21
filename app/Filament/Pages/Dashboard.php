<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getTitle(): string
    {
        $user = Auth::user();
        $greeting = $this->getGreeting();

        if ($user->hasRole('Super Admin')) {
            return "Welcome, {$user->name}! - System Overview";
        } elseif ($user->hasRole('Faculty Admin')) {
            return "Welcome, {$user->name}! - Faculty Dashboard";
        } elseif ($user->hasRole('Department Admin')) {
            return "Welcome, {$user->name}! - Department Dashboard";
        } elseif ($user->hasRole('Verifier')) {
            return "Welcome, {$user->name}! - Verification Dashboard";
        }

        return "Welcome, {$user->name}!";
    }

    private function getGreeting(): string
    {
        $hour = now()->hour;

        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 17) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }

    public function getWidgets(): array
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            return [
                // Stats Cards (5 key metrics)
                \App\Filament\Widgets\SystemStatsWidget::class,

                // Charts (4 different chart types)
                \App\Filament\Widgets\ComprehensiveAnalyticsWidget::class,
                \App\Filament\Widgets\TranscriptRequestsChartWidget::class,
                \App\Filament\Widgets\TranscriptStatusChartWidget::class,
                \App\Filament\Widgets\DeliveryAnalyticsWidget::class,

                // Tables (3 comprehensive tables)
                \App\Filament\Widgets\RecentTranscriptRequestsTableWidget::class,
                \App\Filament\Widgets\RecentTranscriptsTableWidget::class,
                \App\Filament\Widgets\RecentActivityTableWidget::class,
            ];
        } elseif ($user->hasRole('Faculty Admin')) {
            return [
                \App\Filament\Widgets\SystemStatsWidget::class,
                \App\Filament\Widgets\TranscriptRequestsChartWidget::class,
                \App\Filament\Widgets\TranscriptStatusChartWidget::class,
                \App\Filament\Widgets\RecentTranscriptRequestsTableWidget::class,
                \App\Filament\Widgets\RecentActivityTableWidget::class,
            ];
        } elseif ($user->hasRole('Department Admin')) {
            return [
                \App\Filament\Widgets\SystemStatsWidget::class,
                \App\Filament\Widgets\TranscriptRequestsChartWidget::class,
                \App\Filament\Widgets\RecentTranscriptRequestsTableWidget::class,
                \App\Filament\Widgets\RecentActivityTableWidget::class,
            ];
        } elseif ($user->hasRole('Verifier')) {
            return [
                \App\Filament\Widgets\SystemStatsWidget::class,
            ];
        }

        return [
            \App\Filament\Widgets\SystemStatsWidget::class,
        ];
    }
}
