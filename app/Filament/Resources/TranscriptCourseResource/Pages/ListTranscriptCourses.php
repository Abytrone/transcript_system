<?php

namespace App\Filament\Resources\TranscriptCourseResource\Pages;

use App\Filament\Resources\TranscriptCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTranscriptCourses extends ListRecords
{
    protected static string $resource = TranscriptCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
