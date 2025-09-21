<?php

namespace App\Filament\Widgets;

use App\Models\Faculty;
use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class FacultyStatsWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        $user = Auth::user();

        // If user is not faculty admin, show all faculties
        if (!$user->hasRole('Faculty Admin') || !$user->faculty_id) {
            return $this->getAllFacultiesStats();
        }

        // Show stats for specific faculty
        return $this->getFacultyStats($user->faculty_id);
    }

    private function getAllFacultiesStats(): array
    {
        $faculties = Faculty::withCount(['departments', 'students'])->get();

        $stats = [];
        foreach ($faculties as $faculty) {
            $stats[] = Stat::make($faculty->name, $faculty->students_count)
                ->description($faculty->departments_count . ' departments')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary');
        }

        return $stats;
    }

    private function getFacultyStats(int $facultyId): array
    {
        $faculty = Faculty::find($facultyId);
        $totalStudents = Student::whereHas('department', function ($query) use ($facultyId) {
            $query->where('faculty_id', $facultyId);
        })->count();

        $totalTranscripts = Transcript::whereHas('student.department', function ($query) use ($facultyId) {
            $query->where('faculty_id', $facultyId);
        })->count();

        $pendingRequests = TranscriptRequest::whereHas('student.department', function ($query) use ($facultyId) {
            $query->where('faculty_id', $facultyId);
        })->where('status', 'pending')->count();

        return [
            Stat::make('Faculty Students', $totalStudents)
                ->description('Total students in ' . $faculty->name)
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Issued Transcripts', $totalTranscripts)
                ->description('Transcripts from this faculty')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Pending Requests', $pendingRequests)
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
