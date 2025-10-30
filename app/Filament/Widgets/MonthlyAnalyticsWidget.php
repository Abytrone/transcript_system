<?php

namespace App\Filament\Widgets;

use App\Models\Transcript;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Analytics Overview';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        // Get data for the last 6 months: number of transcripts per month
        $months = [];
        $transcriptsData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');

            $count = Transcript::whereYear('issued_at', $month->year)
                ->whereMonth('issued_at', $month->month)
                ->count();
            $transcriptsData[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transcripts Issued',
                    'data' => $transcriptsData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
        ];
    }
}
