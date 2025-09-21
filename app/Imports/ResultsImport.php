<?php

namespace App\Imports;

use App\Models\Result;
use App\Models\Student;
use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;

class ResultsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find student by student_id (index number)
        $student = Student::where('student_id', strtoupper(trim($row['student_id'])))->first();
        if (!$student) {
            throw new \Exception("Student with ID '{$row['student_id']}' not found. Please ensure the student exists in the system.");
        }

        // Find course by code
        $course = Course::where('code', strtoupper(trim($row['course_code'])))->first();
        if (!$course) {
            throw new \Exception("Course with code '{$row['course_code']}' not found. Please ensure the course exists in the system.");
        }

        // Calculate GPA based on score
        $gpa = $this->calculateGPA($row['score']);

        // Determine grade based on score
        $grade = $this->determineGrade($row['score']);

        return new Result([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'score' => (int) $row['score'],
            'grade' => $grade,
            'gpa' => $gpa,
            'is_resit' => strtolower(trim($row['is_resit'] ?? 'no')) === 'yes',
            'academic_year' => (int) $row['academic_year'],
            'semester' => (int) $row['semester'],
        ]);
    }

    /**
     * Calculate GPA based on score
     */
    private function calculateGPA($score): float
    {
        $score = (int) $score;

        if ($score >= 80) return 4.0;
        if ($score >= 75) return 3.5;
        if ($score >= 70) return 3.0;
        if ($score >= 65) return 2.5;
        if ($score >= 60) return 2.0;
        if ($score >= 55) return 1.5;
        if ($score >= 50) return 1.0;
        return 0.0;
    }

    /**
     * Determine grade based on score
     */
    private function determineGrade($score): string
    {
        $score = (int) $score;

        if ($score >= 80) return 'A';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'C+';
        if ($score >= 60) return 'C';
        if ($score >= 55) return 'D+';
        if ($score >= 50) return 'D';
        return 'F';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'string', 'max:255', 'exists:students,student_id'],
            'course_code' => ['required', 'string', 'max:255', 'exists:courses,code'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'academic_year' => ['required', 'integer', 'min:2000', 'max:2030'],
            'semester' => ['required', 'integer', 'in:1,2'],
            'is_resit' => ['nullable', 'string', 'in:yes,no'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'student_id.required' => 'Student ID (Index Number) is required.',
            'student_id.exists' => 'Student with this ID does not exist in the system.',
            'course_code.required' => 'Course code is required.',
            'course_code.exists' => 'Course with this code does not exist in the system.',
            'score.required' => 'Score is required.',
            'score.integer' => 'Score must be a number.',
            'score.min' => 'Score must be at least 0.',
            'score.max' => 'Score must not exceed 100.',
            'academic_year.required' => 'Academic year is required.',
            'academic_year.integer' => 'Academic year must be a number.',
            'semester.required' => 'Semester is required.',
            'semester.in' => 'Semester must be 1 or 2.',
            'is_resit.in' => 'Is resit must be yes or no.',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
