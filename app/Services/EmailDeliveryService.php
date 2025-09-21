<?php

namespace App\Services;

use App\Models\Transcript;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailDeliveryService
{
    /**
     * Send transcript via email
     */
    public function sendTranscript(Transcript $transcript, string $recipientEmail, ?string $message = null): bool
    {
        try {
            $transcript->load(['student', 'transcriptCourses.course']);

            // Generate PDF content
            $pdfService = app(PdfService::class);
            $pdfContent = $this->generatePdfContent($transcript);

            // Send email
            Mail::send('emails.transcript', [
                'transcript' => $transcript,
                'message' => $message,
                'school' => [
                    'name' => 'School of Hygiene, Tamale',
                    'address' => 'Tamale, Northern Region, Ghana',
                    'phone' => '+233 24 123 4567',
                    'email' => 'info@schoolofhygiene.edu.gh',
                ],
            ], function ($mail) use ($transcript, $recipientEmail, $pdfContent) {
                $mail->to($recipientEmail)
                    ->subject('Official Transcript - ' . $transcript->transcript_number)
                    ->attachData($pdfContent, 'transcript-' . $transcript->transcript_number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            // Update transcript with email tracking
            $transcript->update([
                'recipient_email' => $recipientEmail,
                'email_sent_at' => now(),
                'delivery_method' => 'email',
            ]);

            Log::info('Transcript sent via email', [
                'transcript_id' => $transcript->id,
                'recipient_email' => $recipientEmail,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send transcript via email', [
                'transcript_id' => $transcript->id,
                'recipient_email' => $recipientEmail,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generate PDF content for email attachment
     */
    private function generatePdfContent(Transcript $transcript): string
    {
        $pdfService = app(PdfService::class);

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'transcript_') . '.pdf';

        // Generate PDF to file
        $pdfService->generateTranscriptPdfToFile($transcript, $tempFile);

        // Read file content
        $content = file_get_contents($tempFile);

        // Clean up
        unlink($tempFile);

        return $content;
    }

    /**
     * Send bulk transcripts via email
     */
    public function sendBulkTranscripts(array $transcriptIds, string $recipientEmail, ?string $message = null): array
    {
        $results = [];

        foreach ($transcriptIds as $transcriptId) {
            $transcript = Transcript::find($transcriptId);

            if ($transcript) {
                $success = $this->sendTranscript($transcript, $recipientEmail, $message);
                $results[] = [
                    'transcript_id' => $transcriptId,
                    'transcript_number' => $transcript->transcript_number,
                    'student_name' => $transcript->student->full_name,
                    'success' => $success,
                ];
            }
        }

        return $results;
    }
}
