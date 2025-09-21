<?php

namespace App\Filament\Resources\TranscriptResource\Pages;

use App\Filament\Resources\TranscriptResource;
use App\Services\PdfService;
use App\Services\EmailDeliveryService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ViewTranscript extends ViewRecord
{
    protected static string $resource = TranscriptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_transcript')
                ->label('Generate Transcript')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(fn () => route('student.transcript', $this->record->student))
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->record->status !== 'draft'),

            Actions\Action::make('send_email')
                ->label('Send Email')
                ->icon('heroicon-o-envelope')
                ->color('primary')
                ->form([
                    Forms\Components\TextInput::make('recipient_email')
                        ->label('Recipient Email')
                        ->email()
                        ->required()
                        ->default(fn () => $this->record->student->email),
                    Forms\Components\Textarea::make('message')
                        ->label('Additional Message')
                        ->rows(3)
                        ->placeholder('Optional message to include with the transcript...'),
                ])
                ->action(function (array $data) {
                    $emailService = app(EmailDeliveryService::class);
                    $success = $emailService->sendTranscript($this->record, $data['recipient_email'], $data['message']);

                    if ($success) {
                        Notification::make()
                            ->title('Transcript sent successfully')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Failed to send transcript')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn (): bool =>
                    $this->record->status === 'issued' &&
                    Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])
                ),

            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        // Eager load the student's results with course information
        $this->record->load(['student.results.course']);

        return $infolist
            ->schema([
                Infolists\Components\Section::make('📋 Transcript Information')
                    ->description('Official transcript details and status')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('transcript_number')
                                    ->label('Transcript Number')
                                    ->icon('heroicon-o-hashtag')
                                    ->weight('bold')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->icon('heroicon-o-shield-check')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'draft' => 'warning',
                                        'issued' => 'success',
                                        'verified' => 'primary',
                                    }),
                            ]),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('issued_at')
                                    ->label('Issued Date')
                                    ->icon('heroicon-o-calendar-days')
                                    ->dateTime()
                                    ->placeholder('Not issued')
                                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                                Infolists\Components\TextEntry::make('issuedBy.name')
                                    ->label('Issued By')
                                    ->icon('heroicon-o-user')
                                    ->placeholder('Not issued')
                                    ->color(fn ($state) => $state ? 'info' : 'gray'),
                            ]),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('delivery_method')
                                    ->label('Delivery Method')
                                    ->icon('heroicon-o-truck')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pickup' => 'warning',
                                        'email' => 'primary',
                                        'mail' => 'info',
                                        default => 'gray',
                                    })
                                    ->placeholder('Not set'),

                                Infolists\Components\TextEntry::make('recipient_email')
                                    ->label('Recipient Email')
                                    ->icon('heroicon-o-envelope')
                                    ->placeholder('Not set')
                                    ->color(fn ($state) => $state ? 'info' : 'gray'),

                                Infolists\Components\TextEntry::make('email_sent_at')
                                    ->label('Email Sent Date')
                                    ->icon('heroicon-o-paper-airplane')
                                    ->dateTime()
                                    ->placeholder('Not sent')
                                    ->color(fn ($state) => $state ? 'success' : 'gray'),
                            ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('👨‍🎓 Student Information')
                    ->description('Personal and academic details')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('student.student_id')
                                    ->label('Student ID')
                                    ->icon('heroicon-o-identification')
                                    ->weight('bold')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('student.full_name')
                                    ->label('Full Name')
                                    ->icon('heroicon-o-user')
                                    ->weight('bold')
                                    ->color('success'),
                            ]),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('student.email')
                                    ->label('Email Address')
                                    ->icon('heroicon-o-envelope')
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('year_of_completion')
                                    ->label('Year of Completion')
                                    ->icon('heroicon-o-academic-cap')
                                    ->color('warning'),
                            ]),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('student.department.name')
                                    ->label('Department')
                                    ->icon('heroicon-o-building-office')
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('program')
                                    ->label('Program of Study')
                                    ->icon('heroicon-o-book-open')
                                    ->color('primary'),
                            ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('🏆 Academic Performance')
                    ->description('Academic achievements and performance metrics')
                    ->icon('heroicon-o-trophy')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('cgpa')
                                    ->label('Cumulative GPA')
                                    ->icon('heroicon-o-chart-bar')
                                    ->numeric(2)
                                    ->weight('bold')
                                    ->color(fn (string $state): string => match (true) {
                                        $state >= 3.6 => 'success',
                                        $state >= 3.0 => 'primary',
                                        $state >= 2.0 => 'warning',
                                        default => 'danger',
                                    }),

                                Infolists\Components\TextEntry::make('class_of_degree')
                                    ->label('Class of Degree')
                                    ->icon('heroicon-o-star')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'First Class' => 'success',
                                        'Second Class Upper' => 'primary',
                                        'Second Class Lower' => 'warning',
                                        'Third Class' => 'danger',
                                        'Pass' => 'gray',
                                    }),
                            ]),

                        Infolists\Components\Grid::make(1)
                            ->schema([
                                Infolists\Components\TextEntry::make('performance_summary')
                                    ->label('Performance Summary')
                                    ->formatStateUsing(function ($record) {
                                        $cgpa = $record->cgpa;
                                        $class = $record->class_of_degree;

                                        $summary = match (true) {
                                            $cgpa >= 3.6 => "🌟 Outstanding academic performance with First Class honors",
                                            $cgpa >= 3.0 => "⭐ Excellent academic achievement with Second Class Upper",
                                            $cgpa >= 2.0 => "📈 Good academic progress with Second Class Lower",
                                            default => "📚 Academic performance with room for improvement"
                                        };

                                        return $summary;
                                    })
                                    ->icon('heroicon-o-light-bulb')
                                    ->color('info')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('📚 Course Records')
                    ->description('Complete academic transcript with all courses and grades')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Infolists\Components\ViewEntry::make('course_records')
                            ->view('filament.infolists.course-records-table')
                            ->viewData(function ($record) {
                                return [
                                    'results' => $record->student->results ?? collect(),
                                ];
                            }),
                    ])
                    ->collapsible(),
            ]);
    }
}
