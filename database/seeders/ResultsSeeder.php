<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use App\Models\Result;

class ResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found. Please run the other seeders first.');
            return;
        }

        $academicYears = ['2020/2021', '2021/2022', '2022/2023', '2023/2024'];
        $grades = ['A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'];

        // Grade to GPA mapping
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

        // Grade to score range mapping
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
            // Assign 6-10 courses per student across different semesters
            $numCourses = rand(6, 10);
            $assignedCourses = $courses->random($numCourses);

            foreach ($assignedCourses as $course) {
                $academicYear = $academicYears[array_rand($academicYears)];
                $semester = rand(1, 2);
                $grade = $grades[array_rand($grades)];

                // Generate score based on grade
                $scoreRange = $gradeToScoreRange[$grade];
                $score = rand($scoreRange[0], $scoreRange[1]);

                // Get GPA from grade
                $gpa = $gradeToGpa[$grade];

                Result::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'score' => $score,
                    'grade' => $grade,
                    'gpa' => $gpa,
                    'is_resit' => rand(0, 1) && $grade === 'F', // Only mark as resit if it's a failed grade
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                ]);
            }
        }

        $this->command->info('Results seeded successfully!');
    }
}
