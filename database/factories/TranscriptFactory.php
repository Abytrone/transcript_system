<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Transcript;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transcript>
 */
class TranscriptFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var class-string<Transcript>
	 */
	protected $model = Transcript::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$student = Student::inRandomOrder()->first() ?? Student::factory()->create();
		$user = User::inRandomOrder()->first() ?? User::factory()->create();
		$year = fake()->numberBetween(2020, (int) date('Y'));
		$uuid = (string) Str::uuid();
		$number = 'SHT-' . strtoupper(Str::random(4)) . '-' . fake()->unique()->numerify('####');
		$cgpa = fake()->randomFloat(2, 1.0, 4.0);

		// Determine class of degree based on CGPA
		$classOfDegree = match (true) {
			$cgpa >= 3.6 => 'First Class',
			$cgpa >= 3.0 => 'Second Class Upper',
			$cgpa >= 2.0 => 'Second Class Lower',
			$cgpa >= 1.0 => 'Third Class',
			default => 'Pass',
		};

		return [
			'uuid' => $uuid,
			'transcript_number' => $number,
			'student_id' => $student->id,
			'program' => $student->program,
			'year_of_completion' => $year,
			'cgpa' => $cgpa,
			'class_of_degree' => $classOfDegree,
			'qr_code' => 'https://transcript.schoolofhygiene.edu.gh/verify/' . $uuid,
			'status' => fake()->randomElement(['draft', 'issued', 'verified']),
			'issued_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
			'issued_by' => fake()->optional(0.7)->randomElement([$user->id]),
		];
	}
}
