<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\TranscriptRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TranscriptRequest>
 */
class TranscriptRequestFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var class-string<TranscriptRequest>
	 */
	protected $model = TranscriptRequest::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$student = Student::inRandomOrder()->first() ?? Student::factory()->create();
		$user = User::inRandomOrder()->first() ?? User::factory()->create();
		$requestNumber = 'REQ-' . fake()->unique()->numerify('####') . '-' . date('Y');

		return [
			'student_id' => $student->id,
			'request_type' => fake()->randomElement(['official', 'unofficial']),
			'delivery_method' => fake()->randomElement(['email', 'pickup', 'mail']),
			'recipient_email' => fake()->optional(0.8)->safeEmail(),
			'recipient_address' => fake()->optional(0.3)->address(),
			'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
			'remarks' => fake()->optional()->sentence(),
			'handled_by' => fake()->optional(0.6)->randomElement([$user->id]),
			'approved_at' => fake()->optional(0.5)->dateTimeBetween('-6 months', 'now'),
			'rejected_at' => fake()->optional(0.1)->dateTimeBetween('-6 months', 'now'),
			'completed_at' => fake()->optional(0.4)->dateTimeBetween('-3 months', 'now'),
			'transcript_id' => null,
			'request_number' => $requestNumber,
		];
	}
}
