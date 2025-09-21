<?php

namespace App\Filament\Resources\TranscriptCourseResource\Pages;

use App\Filament\Resources\TranscriptCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTranscriptCourse extends EditRecord
{
    protected static string $resource = TranscriptCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
