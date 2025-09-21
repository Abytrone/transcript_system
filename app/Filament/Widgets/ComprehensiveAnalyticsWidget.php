<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ComprehensiveAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'System Analytics Overview';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get data for the last 12 months
        $months = [];
        $transcriptData = [];
        $requestData = [];
        $verificationData = [];
        $statementData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $months[] = $date->format('M Y');

            // Transcripts issued
            $transcriptData[] = Transcript::where('status', 'issued')
                ->whereMonth('issued_at', $date->month)
                ->whereYear('issued_at', $date->year)
                ->count();

            // Requests received
            $requestData[] = TranscriptRequest::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

        }

        return [
            'datasets' => [
                [
                    'label' => 'Transcripts Issued',
                    'data' => $transcriptData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Requests Received',
                    'data' => $requestData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
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
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
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
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }
}
