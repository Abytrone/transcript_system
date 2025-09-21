<?php

namespace Database\Seeders;

use App\Models\Transcript;
use App\Models\VerificationLog;
use Illuminate\Database\Seeder;

class VerificationLogsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$transcripts = Transcript::all();
		foreach ($transcripts as $transcript) {
			VerificationLog::factory()->count(rand(3, 8))->create([
				'transcript_id' => $transcript->id,
				'lookup' => $transcript->transcript_number,
			]);
		}
	}
}
