<?php

namespace App\Filament\Resources\TranscriptRequestResource\Pages;

use App\Filament\Resources\TranscriptRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTranscriptRequest extends ViewRecord
{
    protected static string $resource = TranscriptRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}




