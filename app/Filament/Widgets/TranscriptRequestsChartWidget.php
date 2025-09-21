<?php

namespace App\Filament\Widgets;

use App\Models\TranscriptRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TranscriptRequestsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Transcript Requests Trend';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get requests for the last 12 months
        $requests = TranscriptRequest::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];

        // Fill in missing months with 0
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $labels[] = now()->subMonths($i)->format('M Y');
            $data[] = $requests->where('month', $month)->first()?->count ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Requests',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
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
                    'display' => false,
                ],
            ],
        ];
    }
}
