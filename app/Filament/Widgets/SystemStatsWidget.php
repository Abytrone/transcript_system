<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Course;
use App\Models\Program;
use App\Models\Transcript;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SystemStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $totalPrograms = Program::count();
        $totalTranscripts = Transcript::count();

        return [
            Stat::make('Total Students', number_format($totalStudents))
                ->description('Registered students')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Courses', number_format($totalCourses))
                ->description('Available courses')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Total Programs', number_format($totalPrograms))
                ->description('Academic programs')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Total Transcripts', number_format($totalTranscripts))
                ->description('All transcripts')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
        ];
    }
}
