<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\Result;

class ResultsSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found. Please run the other seeders first.');
            return;
        }

        $grades = ['A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'];

        $gradeToGpa = [
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D+' => 1.5,
            'D' => 1.0,
            'F' => 0.0,
        ];

        $gradeToScoreRange = [
            'A' => [85, 100],
            'B+' => [80, 84],
            'B' => [75, 79],
            'C+' => [70, 74],
            'C' => [65, 69],
            'D+' => [60, 64],
            'D' => [55, 59],
            'F' => [0, 54],
        ];

        foreach ($students as $student) {
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

            // Track failed courses for resits
            $failedCourses = [];

            // Assign courses across semesters
            $coursesPerYear = [
                100 => 4, // First year: 4 courses per year
                200 => 4, // Second year: 4 courses per year
                300 => 3, // Third year: 3 courses per year
            ];

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
                if ($levelCourses->isEmpty() && $programCourses->isNotEmpty()) {
                    $randomCount = min(8, $programCourses->count());
                    if ($randomCount > 0) {
                        $levelCourses = $programCourses->random($randomCount);
                    }
                }

                if ($levelCourses->isEmpty()) {
                    continue;
                }

                $numCourses = min($coursesPerYear[$level] ?? 4, $levelCourses->count());
                if ($numCourses > 0) {
                    $selectedCourses = $levelCourses->random($numCourses);
                } else {
                    continue;
                }

                foreach ($selectedCourses as $course) {
                    // Assign semester based on course semester
                    $semester = $course->semester;

                    // Generate realistic grade (slightly better for higher levels)
                    $gradeWeights = $this->getGradeWeights($level);
                    $grade = $this->weightedRandomGrade($grades, $gradeWeights);

                    $scoreRange = $gradeToScoreRange[$grade];
                    $score = rand($scoreRange[0], $scoreRange[1]);
                    $gpa = $gradeToGpa[$grade];

                    // Create initial result (not a resit)
                    Result::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'score' => $score,
                        'grade' => $grade,
                        'gpa' => $gpa,
                        'is_resit' => false,
                        'academic_year' => $academicYear,
                        'semester' => $semester,
                    ]);

                    // If failed, mark for potential resit
                    if ($grade === 'F') {
                        $failedCourses[] = [
                            'course' => $course,
                            'academic_year' => $academicYear,
                            'semester' => $semester,
                        ];
                    }
                }
            }

            // Create resits for some failed courses (in later periods)
            if (!empty($failedCourses)) {
                $resitCount = min(rand(1, count($failedCourses)), 3, count($failedCourses)); // Max 3 resits per student
                $resitsToCreate = collect($failedCourses)->random($resitCount);

                foreach ($resitsToCreate as $failedCourseData) {
                    $course = $failedCourseData['course'];
                    $originalYear = $failedCourseData['academic_year'];

                    // Resit in a later academic period (next semester or next year)
                    $originalYearStart = (int) substr($originalYear, 0, 4);
                    $originalSemester = $failedCourseData['semester'];
                    $nextYearStart = $originalYearStart + 1;
                    $nextAcademicYear = "{$nextYearStart}/" . ($nextYearStart + 1);

                    // Determine resit period
                    $canResit = false;
                    if ($originalSemester === 1) {
                        // Failed in semester 1, retake in semester 2 of same year
                        $nextAcademicYear = $originalYear;
                        $nextSemester = 2;
                        $canResit = true;
                    } elseif (in_array($nextAcademicYear, $studentAcademicYears)) {
                        // Failed in semester 2, retake in next year (use course's default semester)
                        $nextSemester = $course->semester;
                        $canResit = true;
                    }
                    // If can't resit (failed in semester 2 of last year), skip this resit
                    if (!$canResit) {
                        continue;
                    }

                    // Generate better grade for resit (usually pass)
                    $resitGradeWeights = ['A' => 10, 'B+' => 15, 'B' => 20, 'C+' => 25, 'C' => 20, 'D+' => 7, 'D' => 3, 'F' => 0];
                    $resitGrade = $this->weightedRandomGrade($grades, $resitGradeWeights);

                    // Ensure resit passes (small chance of failing again, but less likely)
                    if (rand(1, 10) <= 8) { // 80% chance to pass resit
                        $resitGrade = $grades[rand(0, 6)]; // A through D (no F)
                    }

                    $resitScoreRange = $gradeToScoreRange[$resitGrade];
                    $resitScore = rand($resitScoreRange[0], $resitScoreRange[1]);
                    $resitGpa = $gradeToGpa[$resitGrade];

                    // Check if resit already exists
                    $existingResit = Result::where('student_id', $student->id)
                        ->where('course_id', $course->id)
                        ->where('academic_year', $nextAcademicYear)
                        ->where('semester', $nextSemester)
                        ->where('is_resit', true)
                        ->first();

                    if (!$existingResit) {
                        Result::create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'score' => $resitScore,
                            'grade' => $resitGrade,
                            'gpa' => $resitGpa,
                            'is_resit' => true,
                            'academic_year' => $nextAcademicYear,
                            'semester' => $nextSemester,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Results seeded successfully!');
    }

    private function getGradeWeights(int $level): array
    {
        // Higher levels get slightly better grades
        if ($level >= 300) {
            return ['A' => 25, 'B+' => 20, 'B' => 20, 'C+' => 15, 'C' => 10, 'D+' => 5, 'D' => 3, 'F' => 2];
        } elseif ($level >= 200) {
            return ['A' => 20, 'B+' => 18, 'B' => 20, 'C+' => 18, 'C' => 12, 'D+' => 6, 'D' => 4, 'F' => 2];
        } else {
            return ['A' => 15, 'B+' => 15, 'B' => 18, 'C+' => 20, 'C' => 15, 'D+' => 10, 'D' => 5, 'F' => 3];
        }
    }

    private function weightedRandomGrade(array $grades, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);

        $cumulative = 0;
        foreach ($grades as $grade) {
            $cumulative += $weights[$grade] ?? 0;
            if ($random <= $cumulative) {
                return $grade;
            }
        }

        return $grades[0];
    }
}
