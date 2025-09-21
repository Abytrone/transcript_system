<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $programs = [
            'Diploma in Environmental Health',
            'Bachelor of Science in Public Health',
            'Bachelor of Science in Nutrition and Dietetics',
            'Diploma in Health Information Management',
            'Certificate in Health Promotion',
        ];

        return [
            'student_id' => 'SOH' . $this->faker->unique()->numberBetween(1000, 9999),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'middle_name' => $this->faker->optional()->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'nationality' => 'Ghanaian',
            'address' => $this->faker->address(),
            'department_id' => Department::factory(),
            'program' => $this->faker->randomElement($programs),
            'year_of_admission' => $this->faker->numberBetween(2018, 2023),
            'year_of_completion' => $this->faker->optional(0.7)->numberBetween(2020, 2024),
            'status' => $this->faker->randomElement(['active', 'graduated', 'dropped']),
            'photo_path' => $this->faker->optional()->imageUrl(200, 200, 'people'),
        ];
    }
}
