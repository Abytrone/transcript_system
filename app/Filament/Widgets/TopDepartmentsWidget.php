<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\TranscriptRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopDepartmentsWidget extends ChartWidget
{
    protected static ?string $heading = 'Top Departments by Requests';

    protected static ?int $sort = 9;

    protected function getData(): array
    {
        $departments = Department::withCount(['students'])
            ->withCount(['transcriptRequests' => function ($query) {
                $query->where('created_at', '>=', now()->subMonths(6));
            }])
            ->orderBy('transcript_requests_count', 'desc')
            ->limit(8)
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
            'rgb(245, 158, 11)',
            'rgb(239, 68, 68)',
            'rgb(139, 92, 246)',
            'rgb(236, 72, 153)',
            'rgb(14, 165, 233)',
            'rgb(34, 197, 94)',
        ];

        foreach ($departments as $index => $department) {
            $labels[] = $department->name;
            $data[] = $department->transcript_requests_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Requests (6 months)',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
