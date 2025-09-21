<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Transcript;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TranscriptCourse>
 */
class TranscriptCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grades = ['A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'];
        $grade = $this->faker->randomElement($grades);

        // Calculate GPA based on grade
        $gpaMap = [
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D+' => 1.5,
            'D' => 1.0,
            'F' => 0.0,
        ];

        return [
            'transcript_id' => Transcript::factory(),
            'course_id' => Course::factory(),
            'grade' => $grade,
            'credit_hours' => $this->faker->numberBetween(2, 4),
            'semester' => $this->faker->randomElement([1, 2]),
            'academic_year' => $this->faker->randomElement(['2020/2021', '2021/2022', '2022/2023', '2023/2024']),
            'gpa' => $gpaMap[$grade],
        ];
    }
}
