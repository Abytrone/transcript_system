<?php

namespace App\Filament\Resources\TranscriptRequestResource\Pages;

use App\Filament\Resources\TranscriptRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranscriptRequests extends ListRecords
{
    protected static string $resource = TranscriptRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
