<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use App\Models\StudentCourse;
use Illuminate\Database\Seeder;

class StudentCoursesSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found. Please run the other seeders first.');
            return;
        }

        foreach ($students as $student) {
            // Get courses from student's program
            if (!$student->program_id) {
                continue;
            }

            $programCourses = $courses->where('program_id', $student->program_id);

            if ($programCourses->isEmpty()) {
                // Fallback to department courses
                $programCourses = $courses->where('department_id', $student->department_id);
            }

            if ($programCourses->isEmpty()) {
                continue;
            }

            // Determine academic years based on admission year
            $admissionYear = $student->year_of_admission;
            $currentYear = date('Y');
            $yearsInSchool = min($currentYear - $admissionYear + 1, 4); // Max 4 years

            $studentAcademicYears = [];
            for ($i = 0; $i < $yearsInSchool; $i++) {
                $yearStart = $admissionYear + $i;
                $yearEnd = $yearStart + 1;
                $studentAcademicYears[] = "{$yearStart}/{$yearEnd}";
            }

            // Assign courses to student based on level and academic year
            $assignedCourseIds = [];

            foreach ($studentAcademicYears as $academicYear) {
                $yearNumber = (int) substr($academicYear, 0, 4) - $admissionYear + 1;
                $level = min(($yearNumber - 1) * 100 + 100, 300);

                // Get courses for this level
                $levelCourses = $programCourses->where('level', $level);
                if ($levelCourses->isEmpty()) {
                    // Fallback to courses within +/- 100 level
                    $levelCourses = $programCourses->filter(function ($course) use ($level) {
                        return abs($course->level - $level) <= 100;
                    });
                }

                // Assign courses for each semester
                foreach ([1, 2] as $semester) {
                    $semesterCourses = $levelCourses->where('semester', $semester);

                    if ($semesterCourses->isEmpty()) {
                        continue;
                    }

                    // Select 2-3 courses per semester
                    $numCourses = min(rand(2, 3), $semesterCourses->count());
                    $selectedCourses = $semesterCourses->random($numCourses);

                    foreach ($selectedCourses as $course) {
                        // Skip if already assigned
                        if (in_array($course->id, $assignedCourseIds)) {
                            continue;
                        }

                        // Check if this course is already in student_courses for this academic year/semester
                        $existing = StudentCourse::where('student_id', $student->id)
                            ->where('course_id', $course->id)
                            ->where('academic_year', $academicYear)
                            ->where('semester', $semester)
                            ->first();

                        if ($existing) {
                            continue;
                        }

                        // Determine status and grade based on results (if any)
                        // First, check for resit result (most recent/passing)
                        $resitResult = \App\Models\Result::where('student_id', $student->id)
                            ->where('course_id', $course->id)
                            ->where('academic_year', '>=', $academicYear) // Resit can be in later period
                            ->where('is_resit', true)
                            ->orderBy('academic_year', 'desc')
                            ->orderBy('semester', 'desc')
                            ->first();

                        // Check for initial result
                        $initialResult = \App\Models\Result::where('student_id', $student->id)
                            ->where('course_id', $course->id)
                            ->where('academic_year', $academicYear)
                            ->where('semester', $semester)
                            ->where('is_resit', false)
                            ->first();

                        $status = 'enrolled';
                        $grade = null;
                        $gpa = null;
                        $isResit = false;

                        // If there's a passing resit, use that
                        if ($resitResult && $resitResult->grade !== 'F') {
                            $status = 'completed';
                            $grade = $resitResult->grade;
                            $gpa = $resitResult->gpa;
                            $isResit = true;
                        } elseif ($initialResult) {
                            if ($initialResult->grade === 'F') {
                                $status = 'failed';
                                $grade = 'F';
                                $gpa = 0.0;
                            } else {
                                $status = 'completed';
                                $grade = $initialResult->grade;
                                $gpa = $initialResult->gpa;
                            }
                        }

                        StudentCourse::create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'academic_year' => $academicYear,
                            'semester' => $semester,
                            'grade' => $grade,
                            'gpa' => $gpa,
                            'credit_hours' => $course->credits,
                            'status' => $status,
                            'is_resit' => $isResit,
                        ]);

                        $assignedCourseIds[] = $course->id;
                    }
                }
            }
        }

        $this->command->info('Student courses seeded successfully!');
    }
}
