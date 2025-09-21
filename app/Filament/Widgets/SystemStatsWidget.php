<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SystemStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalStudents = Student::count();
        $totalTranscripts = Transcript::count();
        $totalRequests = TranscriptRequest::count();
        // Recent activity (last 30 days)
        $recentRequests = TranscriptRequest::where('created_at', '>=', now()->subDays(30))->count();
        $recentTranscripts = Transcript::where('created_at', '>=', now()->subDays(30))->count();

        // Pending requests
        $pendingRequests = TranscriptRequest::where('status', 'pending')->count();

        // Completed transcripts this month
        $completedThisMonth = Transcript::where('status', 'issued')
            ->whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();

        // Email delivery statistics
        $emailDeliveries = Transcript::where('delivery_method', 'email')->count();
        $emailSentToday = Transcript::where('delivery_method', 'email')
            ->whereDate('email_sent_at', today())
            ->count();

        return [
            Stat::make('Total Students', $totalStudents)
                ->description('Registered students')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Transcripts', $totalTranscripts)
                ->description('Issued transcripts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Pending Requests', $pendingRequests)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Email Deliveries', $emailDeliveries)
                ->description('Sent via email')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),
        ];
    }
}
