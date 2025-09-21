<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Transcript;
use App\Models\TranscriptRequest;
use App\Models\VerificationLog;
use App\Models\User;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some students
        $students = Student::take(5)->get();

        if ($students->isEmpty()) {
            $this->command->info('No students found. Please run the student seeder first.');
            return;
        }

        // Get a user to issue transcripts
        $user = User::first();

        // Create sample transcripts
        foreach ($students as $index => $student) {
            $transcript = Transcript::create([
                'uuid' => Str::uuid(),
                'transcript_number' => 'TRN-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'student_id' => $student->id,
                'program' => $student->program->name ?? 'Diploma in Public Health',
                'year_of_completion' => 2023,
                'cgpa' => rand(300, 400) / 100, // Random CGPA between 3.0 and 4.0
                'class_of_degree' => $this->getClassOfDegree(rand(300, 400) / 100),
                'qr_code' => 'QR-' . Str::random(10),
                'status' => 'issued',
                'issued_at' => now()->subDays(rand(1, 30)),
                'issued_by' => $user?->id,
            ]);

            // Create transcript requests for some students
            if ($index < 3) {
                TranscriptRequest::create([
                    'request_number' => 'REQ-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                    'student_id' => $student->id,
                    'request_type' => ['official', 'unofficial'][rand(0, 1)],
                    'delivery_method' => ['email', 'pickup', 'mail'][rand(0, 2)],
                    'recipient_email' => $student->email,
                    'status' => ['pending', 'approved', 'completed'][rand(0, 2)],
                    'transcript_id' => $transcript->id,
                    'handled_by' => $user?->id,
                    'approved_at' => $transcript->status === 'approved' ? now()->subDays(rand(1, 10)) : null,
                    'completed_at' => $transcript->status === 'completed' ? now()->subDays(rand(1, 5)) : null,
                ]);
            }
        }

        // Create some verification logs
        $transcripts = Transcript::take(3)->get();
        foreach ($transcripts as $index => $transcript) {
            VerificationLog::create([
                'lookup' => $transcript->uuid,
                'transcript_id' => $transcript->id,
                'result' => ['valid', 'invalid'][rand(0, 1)],
                'ip_address' => '192.168.1.' . rand(100, 200),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => now()->subDays(rand(1, 15)),
            ]);
        }

        $this->command->info('Sample data created successfully!');
    }

    private function getClassOfDegree(float $cgpa): string
    {
        if ($cgpa >= 3.6) return 'First Class';
        if ($cgpa >= 3.0) return 'Second Class Upper';
        if ($cgpa >= 2.5) return 'Second Class Lower';
        if ($cgpa >= 2.0) return 'Third Class';
        return 'Pass';
    }
}
