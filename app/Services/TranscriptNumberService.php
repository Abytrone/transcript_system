<?php

namespace App\Services;

use App\Models\Transcript;
use App\Models\Student;

class TranscriptNumberService
{
    /**
     * Generate a unique transcript number based on pattern
     * Pattern: SHT-YYYY-XXXX (e.g., SHT-2025-0001)
     */
    public function generateTranscriptNumber(): string
    {
        $currentYear = date('Y');
        $prefix = "SHT-{$currentYear}-";

        // Get the last transcript number for this year
        $lastTranscript = Transcript::where('transcript_number', 'like', $prefix . '%')
            ->orderBy('transcript_number', 'desc')
            ->first();

        if ($lastTranscript) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastTranscript->transcript_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            // Check if there are any existing transcripts to determine starting number
            $existingCount = Transcript::count();
            $nextNumber = $existingCount + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validate if a transcript number follows the correct pattern
     */
    public function validateTranscriptNumber(string $transcriptNumber): bool
    {
        // Pattern: SHT-YYYY-XXXX
        $pattern = '/^SHT-\d{4}-\d{4}$/';
        return preg_match($pattern, $transcriptNumber) === 1;
    }

    /**
     * Extract year from transcript number
     */
    public function extractYear(string $transcriptNumber): ?int
    {
        if ($this->validateTranscriptNumber($transcriptNumber)) {
            preg_match('/^SHT-(\d{4})-\d{4}$/', $transcriptNumber, $matches);
            return isset($matches[1]) ? (int) $matches[1] : null;
        }
        return null;
    }

    /**
     * Extract sequence number from transcript number
     */
    public function extractSequenceNumber(string $transcriptNumber): ?int
    {
        if ($this->validateTranscriptNumber($transcriptNumber)) {
            preg_match('/^SHT-\d{4}-(\d{4})$/', $transcriptNumber, $matches);
            return isset($matches[1]) ? (int) $matches[1] : null;
        }
        return null;
    }
}
