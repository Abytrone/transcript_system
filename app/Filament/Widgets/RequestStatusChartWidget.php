<?php

namespace App\Filament\Widgets;

use App\Models\TranscriptRequest;
use Filament\Widgets\ChartWidget;

class RequestStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Request Status Distribution';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $statusCounts = TranscriptRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $labels = [];
        $data = [];
        $colors = [];

        $statusConfig = [
            'pending' => ['label' => 'Pending', 'color' => 'rgb(245, 158, 11)'],
            'approved' => ['label' => 'Approved', 'color' => 'rgb(34, 197, 94)'],
            'rejected' => ['label' => 'Rejected', 'color' => 'rgb(239, 68, 68)'],
            'completed' => ['label' => 'Completed', 'color' => 'rgb(59, 130, 246)'],
        ];

        foreach ($statusConfig as $status => $config) {
            $labels[] = $config['label'];
            $data[] = $statusCounts[$status] ?? 0;
            $colors[] = $config['color'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Requests',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
