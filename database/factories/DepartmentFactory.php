<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            ['name' => 'Environmental Health', 'code' => 'ENV'],
            ['name' => 'Public Health', 'code' => 'PUB'],
            ['name' => 'Nutrition and Dietetics', 'code' => 'NUT'],
            ['name' => 'Health Information Management', 'code' => 'HIM'],
            ['name' => 'Health Promotion', 'code' => 'HPR'],
            ['name' => 'Epidemiology', 'code' => 'EPI'],
        ];

        $department = $this->faker->randomElement($departments);

        return [
            'name' => $department['name'],
            'code' => $department['code'],
            'description' => $this->faker->paragraph(),
            'faculty_id' => Faculty::factory(),
            'head_name' => $this->faker->name(),
            'head_email' => $this->faker->unique()->safeEmail(),
            'head_phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
