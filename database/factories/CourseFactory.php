<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $courses = [
            ['title' => 'Introduction to Public Health', 'code' => 'PH101'],
            ['title' => 'Environmental Health Principles', 'code' => 'EH101'],
            ['title' => 'Nutrition Fundamentals', 'code' => 'NF101'],
            ['title' => 'Health Information Systems', 'code' => 'HIS101'],
            ['title' => 'Epidemiology', 'code' => 'EPI201'],
            ['title' => 'Health Promotion Strategies', 'code' => 'HPS201'],
            ['title' => 'Research Methods', 'code' => 'RM301'],
            ['title' => 'Health Policy and Management', 'code' => 'HPM401'],
        ];

        $course = $this->faker->randomElement($courses);

        return [
            'code' => $course['code'],
            'title' => $course['title'],
            'description' => $this->faker->paragraph(),
            'credits' => $this->faker->numberBetween(2, 4),
            'department_id' => Department::factory(),
            'level' => $this->faker->randomElement([100, 200, 300, 400]),
            'semester' => $this->faker->randomElement([1, 2]),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
