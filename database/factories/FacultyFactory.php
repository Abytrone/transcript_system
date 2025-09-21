<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faculty>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faculties = [
            ['name' => 'Faculty of Health Sciences', 'code' => 'FHS'],
            ['name' => 'Faculty of Environmental Health', 'code' => 'FEH'],
            ['name' => 'Faculty of Public Health', 'code' => 'FPH'],
            ['name' => 'Faculty of Nutrition and Dietetics', 'code' => 'FND'],
        ];

        $faculty = $this->faker->randomElement($faculties);

        return [
            'name' => $faculty['name'],
            'code' => $faculty['code'],
            'description' => $this->faker->paragraph(),
            'dean_name' => $this->faker->name(),
            'dean_email' => $this->faker->unique()->safeEmail(),
            'dean_phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
