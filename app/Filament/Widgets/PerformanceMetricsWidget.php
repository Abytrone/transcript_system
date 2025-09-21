<?php

namespace App\Filament\Widgets;

use App\Models\Transcript;
use App\Models\TranscriptRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PerformanceMetricsWidget extends BaseWidget
{
    protected static ?int $sort = 9;

    protected function getStats(): array
    {
        // Calculate processing times and efficiency metrics
        $avgProcessingTime = TranscriptRequest::where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        // Request completion rate
        $totalRequests = TranscriptRequest::count();
        $completedRequests = TranscriptRequest::where('status', 'completed')->count();
        $completionRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100, 1) : 0;

        // Email delivery success rate
        $emailDeliveries = Transcript::where('delivery_method', 'email')->count();
        $emailSent = Transcript::where('delivery_method', 'email')
            ->whereNotNull('email_sent_at')
            ->count();
        $emailSuccessRate = $emailDeliveries > 0 ? round(($emailSent / $emailDeliveries) * 100, 1) : 0;

        // Security compliance rate
        $totalTranscripts = Transcript::where('status', 'issued')->count();
        $secureTranscripts = Transcript::where('status', 'issued')
            ->whereNotNull('security_hash')
            ->whereNotNull('watermark_text')
            ->where('is_compromised', false)
            ->count();
        $securityComplianceRate = $totalTranscripts > 0 ? round(($secureTranscripts / $totalTranscripts) * 100, 1) : 0;

        // Monthly growth rate
        $currentMonth = Transcript::where('status', 'issued')
            ->whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();
        $lastMonth = Transcript::where('status', 'issued')
            ->whereMonth('issued_at', now()->subMonth()->month)
            ->whereYear('issued_at', now()->subMonth()->year)
            ->count();
        $growthRate = $lastMonth > 0 ? round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;

        return [
            Stat::make('Avg Processing Time', $avgProcessingTime > 0 ? $avgProcessingTime . ' hours' : 'N/A')
                ->description('Request to completion')
                ->descriptionIcon('heroicon-m-clock')
                ->color($avgProcessingTime <= 24 ? 'success' : ($avgProcessingTime <= 72 ? 'warning' : 'danger')),

            Stat::make('Completion Rate', $completionRate . '%')
                ->description('Requests completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($completionRate >= 90 ? 'success' : ($completionRate >= 70 ? 'warning' : 'danger')),

            Stat::make('Email Success Rate', $emailSuccessRate . '%')
                ->description('Email deliveries')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($emailSuccessRate >= 95 ? 'success' : ($emailSuccessRate >= 80 ? 'warning' : 'danger')),

            Stat::make('Security Compliance', $securityComplianceRate . '%')
                ->description('Secure transcripts')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($securityComplianceRate >= 95 ? 'success' : ($securityComplianceRate >= 80 ? 'warning' : 'danger')),

            Stat::make('Monthly Growth', $growthRate > 0 ? '+' . $growthRate . '%' : $growthRate . '%')
                ->description('Transcript volume')
                ->descriptionIcon($growthRate > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growthRate > 0 ? 'success' : ($growthRate > -10 ? 'warning' : 'danger')),
        ];
    }
}
