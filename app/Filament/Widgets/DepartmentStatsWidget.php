<?php

namespace App\Filament\Widgets;

use App\Models\Department;
use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DepartmentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        $user = Auth::user();

        // If user is not department admin, show all departments
        if (!$user->hasRole('Department Admin') || !$user->department_id) {
            return $this->getAllDepartmentsStats();
        }

        // Show stats for specific department
        return $this->getDepartmentStats($user->department_id);
    }

    private function getAllDepartmentsStats(): array
    {
        $departments = Department::withCount(['students', 'courses'])->get();

        $stats = [];
        foreach ($departments as $department) {
            $stats[] = Stat::make($department->name, $department->students_count)
                ->description($department->courses_count . ' courses')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary');
        }

        return $stats;
    }

    private function getDepartmentStats(int $departmentId): array
    {
        $department = Department::find($departmentId);
        $totalStudents = Student::where('department_id', $departmentId)->count();

        $totalTranscripts = Transcript::whereHas('student', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->count();

        $pendingRequests = TranscriptRequest::whereHas('student', function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })->where('status', 'pending')->count();

        return [
            Stat::make('Department Students', $totalStudents)
                ->description('Students in ' . $department->name)
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Issued Transcripts', $totalTranscripts)
                ->description('Transcripts from this department')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Pending Requests', $pendingRequests)
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
