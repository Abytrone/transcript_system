<?php

namespace App\Http\Controllers;

use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StudentAccessController extends Controller
{
    /**
     * Display the student access page
     */
    public function show(string $uuid): View
    {
        $transcript = Transcript::with(['student', 'transcriptCourses.course'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('transcript.student-access', compact('transcript'));
    }

    /**
     * Get transcript for student access
     */
    public function getTranscript(string $uuid): JsonResponse
    {
        $transcript = Transcript::with([
            'student.department.faculty',
            'transcriptCourses.course',
            'issuedBy'
        ])
            ->where('uuid', $uuid)
            ->first();

        if (!$transcript) {
            return response()->json([
                'error' => 'Transcript not found',
            ], 404);
        }


        return response()->json([
            'transcript' => [
                'transcript_number' => $transcript->transcript_number,
                'uuid' => $transcript->uuid,
                'student' => [
                    'name' => $transcript->student->full_name,
                    'student_id' => $transcript->student->student_id,
                    'email' => $transcript->student->email,
                    'department' => $transcript->student->department->name,
                    'faculty' => $transcript->student->department->faculty->name,
                ],
                'program' => $transcript->program,
                'year_of_completion' => $transcript->year_of_completion,
                'cgpa' => $transcript->cgpa,
                'class_of_degree' => $transcript->class_of_degree,
                'status' => $transcript->status,
                'issued_at' => $transcript->issued_at,
                'issued_by' => $transcript->issuedBy?->name,
                'courses' => $transcript->transcriptCourses->map(function ($transcriptCourse) {
                    return [
                        'course_code' => $transcriptCourse->course->code,
                        'course_title' => $transcriptCourse->course->title,
                        'grade' => $transcriptCourse->grade,
                        'credit_hours' => $transcriptCourse->credit_hours,
                        'gpa' => $transcriptCourse->gpa,
                        'semester' => $transcriptCourse->semester,
                        'academic_year' => $transcriptCourse->academic_year,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Download transcript as PDF
     */
    public function download(string $uuid)
    {
        $transcript = Transcript::with([
            'student.department.faculty',
            'transcriptCourses.course',
            'issuedBy'
        ])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Generate PDF using the existing PdfService
        $pdfService = app(\App\Services\PdfService::class);

        return $pdfService->generateTranscriptPdf($transcript);
    }
}
