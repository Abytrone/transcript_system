<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions-widget';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'title' => 'Create New Transcript',
                    'description' => 'Generate a new transcript for a student',
                    'icon' => 'heroicon-o-document-plus',
                    'url' => route('filament.admin.resources.transcripts.create'),
                    'color' => 'primary',
                ],
                [
                    'title' => 'Process Requests',
                    'description' => 'Review and process pending transcript requests',
                    'icon' => 'heroicon-o-clipboard-document-list',
                    'url' => route('filament.admin.resources.transcript-requests.index'),
                    'color' => 'warning',
                ],
                [
                    'title' => 'Add New Student',
                    'description' => 'Register a new student in the system',
                    'icon' => 'heroicon-o-user-plus',
                    'url' => route('filament.admin.resources.students.create'),
                    'color' => 'success',
                ],
                [
                    'title' => 'View Verifications',
                    'description' => 'Monitor transcript verification activity',
                    'icon' => 'heroicon-o-shield-check',
                    'url' => route('filament.admin.resources.verification-logs.index'),
                    'color' => 'info',
                ],
            ],
        ];
    }
}
