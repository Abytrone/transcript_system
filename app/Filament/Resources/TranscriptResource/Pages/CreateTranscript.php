<?php

namespace App\Filament\Resources\TranscriptResource\Pages;

use App\Filament\Resources\TranscriptResource;
use App\Services\TranscriptNumberService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTranscript extends CreateRecord
{
    protected static string $resource = TranscriptResource::class;

    public function mount(): void
    {
        parent::mount();

        // Pre-fill defaults so they appear when issuing
        $this->form->fill([
            'transcript_number' => app(TranscriptNumberService::class)->generateTranscriptNumber(),
            'issued_by' => Auth::id(),
            'issued_at' => today()->format('Y-m-d'),
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['status'] ?? 'draft') === 'issued') {
            $data['issued_by'] = $data['issued_by'] ?? Auth::id();
            $data['issued_at'] = $data['issued_at'] ?? today()->format('Y-m-d');
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
