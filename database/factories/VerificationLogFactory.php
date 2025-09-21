<?php

namespace Database\Factories;

use App\Models\Transcript;
use App\Models\VerificationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerificationLog>
 */
class VerificationLogFactory extends Factory
{
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var class-string<VerificationLog>
	 */
	protected $model = VerificationLog::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$transcript = Transcript::inRandomOrder()->first();
		$lookup = $transcript?->transcript_number ?? strtoupper(fake()->bothify('SHT-????-####'));

		return [
			'lookup' => $lookup,
			'transcript_id' => $transcript?->id,
			'result' => fake()->randomElement(['valid', 'invalid', 'revoked']),
			'ip_address' => fake()->ipv4(),
			'user_agent' => fake()->userAgent(),
		];
	}
}
