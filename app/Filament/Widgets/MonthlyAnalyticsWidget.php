<?php

namespace App\Filament\Widgets;

use App\Models\TranscriptRequest;
use App\Models\Transcript;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Analytics Overview';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get data for the last 6 months
        $months = [];
        $requestsData = [];
        $verificationsData = [];
        $transcriptsData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');

            // Requests
            $requests = TranscriptRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $requestsData[] = $requests;


            // Transcripts issued
            $transcripts = Transcript::whereYear('issued_at', $month->year)
                ->whereMonth('issued_at', $month->month)
                ->count();
            $transcriptsData[] = $transcripts;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Requests',
                    'data' => $requestsData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => false,
                ],
                [
                    'label' => 'Transcripts Issued',
                    'data' => $transcriptsData,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 2,
                    'fill' => false,
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
