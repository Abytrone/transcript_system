<?php

namespace App\Services;

use App\Models\Transcript;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    /**
     * Generate QR code for transcript verification
     */
    public function generateTranscriptQrCode(Transcript $transcript): string
    {
        $verificationUrl = route('transcript.verify', ['uuid' => $transcript->uuid]);

        // Generate QR code as SVG
        $qrCode = QrCode::size(200)
            ->format('svg')
            ->generate($verificationUrl);

        // Store QR code in storage
        $filename = 'qr-codes/transcript-' . $transcript->uuid . '.svg';
        Storage::disk('public')->put($filename, $qrCode);

        // Update transcript with QR code path
        $transcript->update([
            'qr_code' => $verificationUrl,
        ]);

        return Storage::disk('public')->url($filename);
    }

    /**
     * Generate QR code for student access
     */
    public function generateStudentAccessQrCode(Transcript $transcript): string
    {
        $accessUrl = route('transcript.student-access', ['uuid' => $transcript->uuid]);

        // Generate QR code as SVG
        $qrCode = QrCode::size(200)
            ->format('svg')
            ->generate($accessUrl);

        // Store QR code in storage
        $filename = 'qr-codes/student-access-' . $transcript->uuid . '.svg';
        Storage::disk('public')->put($filename, $qrCode);

        return Storage::disk('public')->url($filename);
    }

    /**
     * Get QR code URL for transcript
     */
    public function getTranscriptQrCodeUrl(Transcript $transcript): string
    {
        if ($transcript->qr_code) {
            return $transcript->qr_code;
        }

        return route('transcript.verify', ['uuid' => $transcript->uuid]);
    }

    /**
     * Get QR code image for transcript
     */
    public function getTranscriptQrCodeImage(Transcript $transcript): string
    {
        $filename = 'qr-codes/transcript-' . $transcript->uuid . '.svg';

        if (Storage::disk('public')->exists($filename)) {
            return Storage::disk('public')->url($filename);
        }

        // Generate new QR code if it doesn't exist
        return $this->generateTranscriptQrCode($transcript);
    }
}
