<?php

namespace App\Filament\Widgets;

use App\Models\Transcript;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TranscriptStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Transcript Status Distribution';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $statusData = Transcript::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'draft' => 'rgba(107, 114, 128, 0.8)',
            'issued' => 'rgba(34, 197, 94, 0.8)',
            'verified' => 'rgba(59, 130, 246, 0.8)',
        ];

        foreach ($statusData as $status) {
            $labels[] = ucfirst($status->status);
            $data[] = $status->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transcript Status',
                    'data' => $data,
                    'backgroundColor' => array_values($colors),
                    'borderColor' => array_map(function($color) {
                        return str_replace('0.8', '1', $color);
                    }, array_values($colors)),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
