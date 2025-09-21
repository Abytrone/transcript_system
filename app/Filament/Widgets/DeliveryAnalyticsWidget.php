<?php

namespace App\Filament\Widgets;

use App\Models\Transcript;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DeliveryAnalyticsWidget extends ChartWidget
{
    protected static ?string $heading = 'Delivery Method Distribution';

    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $deliveryMethods = Transcript::select('delivery_method', DB::raw('COUNT(*) as count'))
            ->whereNotNull('delivery_method')
            ->groupBy('delivery_method')
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'pickup' => 'rgba(34, 197, 94, 0.8)',
            'email' => 'rgba(59, 130, 246, 0.8)',
            'mail' => 'rgba(245, 158, 11, 0.8)',
        ];

        foreach ($deliveryMethods as $method) {
            $labels[] = ucfirst($method->delivery_method);
            $data[] = $method->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Delivery Methods',
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
        return 'doughnut';
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
