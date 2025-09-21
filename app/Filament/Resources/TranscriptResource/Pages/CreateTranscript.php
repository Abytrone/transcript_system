<?php

namespace App\Filament\Resources\TranscriptResource\Pages;

use App\Filament\Resources\TranscriptResource;
use App\Services\TranscriptNumberService;
use Filament\Resources\Pages\CreateRecord;

class CreateTranscript extends CreateRecord
{
    protected static string $resource = TranscriptResource::class;

    public function mount(): void
    {
        parent::mount();

        // Pre-fill the transcript number when the form loads
        $this->form->fill([
            'transcript_number' => app(TranscriptNumberService::class)->generateTranscriptNumber(),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
