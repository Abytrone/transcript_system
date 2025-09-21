<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranscriptResource\Pages;
use App\Models\Transcript;
use App\Models\Student;
use App\Services\PdfService;
use App\Services\EmailDeliveryService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class TranscriptResource extends Resource
{
    protected static ?string $model = Transcript::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Transcript Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transcript_number')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Auto-generated transcript number (SHT-YYYY-XXXX format)'),

                Forms\Components\Select::make('student_id')
                    ->label('Student')
                    ->relationship('student', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn (Student $record): string => "{$record->student_id} - {$record->full_name}")
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $student = Student::with(['program', 'department'])->find($state);
                            if ($student) {
                                // Auto-populate program
                                $set('program', $student->program?->name ?? '');

                                // Auto-populate year of completion
                                $set('year_of_completion', $student->year_of_completion);

                                // Auto-calculate CGPA
                                $cgpa = $student->getCumulativeGPA();
                                $set('cgpa', $cgpa);

                                // Auto-determine class of degree
                                $classOfDegree = self::determineClassOfDegree($cgpa);
                                $set('class_of_degree', $classOfDegree);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('program')
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\TextInput::make('year_of_completion')
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\TextInput::make('cgpa')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Automatically calculated from student results'),

                Forms\Components\Select::make('class_of_degree')
                    ->options([
                        'First Class' => 'First Class',
                        'Second Class Upper' => 'Second Class Upper',
                        'Second Class Lower' => 'Second Class Lower',
                        'Third Class' => 'Third Class',
                        'Pass' => 'Pass',
                    ])
                    ->disabled()
                    ->dehydrated(),


                Forms\Components\Select::make('status')
                    ->options(function ($record) {
                        $currentStatus = $record?->status ?? 'draft';

                        return match ($currentStatus) {
                            'draft' => [
                                'draft' => 'Draft',
                                'issued' => 'Issue Transcript',
                            ],
                            'issued' => [
                                'issued' => 'Issued',
                                'verified' => 'Mark as Verified',
                            ],
                            'verified' => [
                                'verified' => 'Verified',
                            ],
                            default => [
                                'draft' => 'Draft',
                                'issued' => 'Issue Transcript',
                            ],
                        };
                    })
                    ->default('draft')
                    ->required()
                    ->live()
                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin']))
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Auto-set issued_by and issued_at when status changes to 'issued'
                        if ($state === 'issued') {
                            $set('issued_by', Auth::id());
                            $set('issued_at', now());
                        }

                        // Auto-set verified_by and verified_at when status changes to 'verified'
                        if ($state === 'verified') {
                            $set('verified_by', Auth::user()->name);
                            $set('verified_at', now());
                        }
                    }),

                Forms\Components\Select::make('issued_by')
                    ->relationship('issuedBy', 'name')
                    ->searchable()
                    ->preload()
                    ->default(Auth::id())
                    ->visible(fn ($get) => $get('status') === 'issued'),

                Forms\Components\DateTimePicker::make('issued_at')
                    ->visible(fn ($get) => $get('status') === 'issued')
                    ->default(now()),

                Forms\Components\DateTimePicker::make('verified_at')
                    ->label('Verified Date')
                    ->visible(fn ($get) => $get('status') === 'verified')
                    ->default(now())
                    ->disabled(),

                Forms\Components\TextInput::make('verified_by')
                    ->label('Verified By')
                    ->visible(fn ($get) => $get('status') === 'verified')
                    ->default(Auth::user()->name)
                    ->disabled(),

                Forms\Components\Section::make('Delivery Information')
                    ->schema([
                        Forms\Components\Select::make('delivery_method')
                            ->label('Delivery Method')
                            ->options([
                                'pickup' => 'Pickup',
                                'email' => 'Email',
                                'mail' => 'Physical Mail',
                            ])
                            ->default('pickup')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                // Clear recipient email if not email delivery
                                if ($state !== 'email') {
                                    $set('recipient_email', null);
                                } else {
                                    // Auto-populate with student email for email delivery
                                    $studentId = $get('student_id');
                                    if ($studentId) {
                                        $student = Student::find($studentId);
                                        $set('recipient_email', $student?->email ?? '');
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('recipient_email')
                            ->label('Recipient Email')
                            ->email()
                            ->required(fn ($get) => $get('delivery_method') === 'email')
                            ->visible(fn ($get) => $get('delivery_method') === 'email')
                            ->placeholder('Enter recipient email address...')
                            ->helperText('Required when delivery method is email')
                            ->rules([
                                'required_if:delivery_method,email',
                                'email',
                            ]),

                        Forms\Components\Textarea::make('delivery_notes')
                            ->label('Delivery Notes')
                            ->rows(3)
                            ->placeholder('Additional notes for delivery...'),
                    ])
                    ->visible(fn ($get) => $get('status') === 'issued')
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transcript_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year_of_completion')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cgpa')
                    ->label('CGPA')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        $state >= 5.0 => 'success',
                        $state >= 4.9 => 'primary',
                        $state >= 3.5 => 'warning',
                        $state >= 2.0 => 'danger',
                        $state >= 2.5 => 'gray',
                        $state >= 1.0 => 'gray',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('class_of_degree')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'First Class' => 'success',
                        'Second Class Upper' => 'primary',
                        'Second Class Lower' => 'warning',
                        'Third Class' => 'danger',
                        'Pass' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'issued' => 'success',
                        'verified' => 'primary',
                    }),
                Tables\Columns\TextColumn::make('issuedBy.name')
                    ->label('Issued By')
                    ->placeholder('Not issued'),
                Tables\Columns\TextColumn::make('issued_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not issued')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Delivery')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pickup' => 'gray',
                        'email' => 'primary',
                        'mail' => 'warning',
                        default => 'gray',
                    })
                    ->placeholder('Not set')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('recipient_email')
                    ->label('Email Sent To')
                    ->placeholder('Not sent')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_sent_at')
                    ->label('Email Sent')
                    ->dateTime()
                    ->placeholder('Not sent')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued',
                        'verified' => 'Verified',
                    ]),
                Tables\Filters\SelectFilter::make('class_of_degree')
                    ->options([
                        'First Class' => 'First Class',
                        'Second Class Upper' => 'Second Class Upper',
                        'Second Class Lower' => 'Second Class Lower',
                        'Third Class' => 'Third Class',
                        'Pass' => 'Pass',
                    ]),
                Tables\Filters\SelectFilter::make('delivery_method')
                    ->label('Delivery Method')
                    ->options([
                        'pickup' => 'Pickup',
                        'email' => 'Email',
                        'mail' => 'Physical Mail',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Action::make('generate_transcript')
                        ->label('Generate Transcript')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn (Transcript $record) => route('student.transcript', $record->student))
                        ->openUrlInNewTab()
                        ->visible(fn (Transcript $record): bool => $record->status !== 'draft'),

                    Action::make('send_email')
                        ->label('Send Email')
                        ->icon('heroicon-o-envelope')
                        ->color('primary')
                        ->form([
                            Forms\Components\TextInput::make('recipient_email')
                                ->label('Recipient Email')
                                ->email()
                                ->required()
                                ->default(fn (Transcript $record) => $record->student->email),
                            Forms\Components\Textarea::make('message')
                                ->label('Additional Message')
                                ->rows(3)
                                ->placeholder('Optional message to include with the transcript...'),
                        ])
                        ->action(function (Transcript $record, array $data) {
                            $emailService = app(EmailDeliveryService::class);
                            $success = $emailService->sendTranscript($record, $data['recipient_email'], $data['message']);

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
                        ->visible(fn (Transcript $record): bool =>
                            $record->status === 'issued' &&
                            Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])
                        ),


                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('bulk_generate_pdf')
                        ->label('Generate PDFs')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $pdfService = app(PdfService::class);
                            $count = 0;

                            foreach ($records as $record) {
                                if ($record->status !== 'draft') {
                                    try {
                                        $pdfService->generateTranscriptPdf($record);
                                        $count++;
                                    } catch (\Exception $e) {
                                        // Log error but continue
                                    }
                                }
                            }

                            Notification::make()
                                ->title("Generated {$count} PDFs successfully")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('bulk_send_email')
                        ->label('Send Emails')
                        ->icon('heroicon-o-envelope')
                        ->color('primary')
                        ->form([
                            Forms\Components\TextInput::make('recipient_email')
                                ->label('Recipient Email')
                                ->email()
                                ->required(),
                            Forms\Components\Textarea::make('message')
                                ->label('Additional Message')
                                ->rows(3)
                                ->placeholder('Optional message to include with the transcripts...'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $emailService = app(EmailDeliveryService::class);
                            $transcriptIds = $records->pluck('id')->toArray();
                            $results = $emailService->sendBulkTranscripts($transcriptIds, $data['recipient_email'], $data['message']);

                            $successCount = collect($results)->where('success', true)->count();
                            $totalCount = count($results);

                            Notification::make()
                                ->title("Sent {$successCount} of {$totalCount} transcripts successfully")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion()
                        ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin', 'department_admin'])),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranscripts::route('/'),
            'create' => Pages\CreateTranscript::route('/create'),
            'view' => Pages\ViewTranscript::route('/{record}'),
            'edit' => Pages\EditTranscript::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        // Apply role-based filtering
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            // Super Admin can see all transcripts
            return $query;
            } elseif ($user->hasRole('faculty_admin')) {
            // Faculty Admin can see transcripts from their faculty
            return $query->whereHas('student.department', function ($q) use ($user) {
                $q->where('faculty_id', $user->faculty_id);
            });
        } elseif ($user->hasRole('department_admin')) {
            // Department Admin can see transcripts from their department
            return $query->whereHas('student', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        } elseif ($user->hasRole('verifier')) {
            // Verifiers can only see issued transcripts
            return $query->where('status', 'issued');
        }

        // Default: no access
        return $query->whereRaw('1 = 0');
    }

    /**
     * Determine class of degree based on CGPA
     */
    public static function determineClassOfDegree(float $cgpa): string
    {
        return match (true) {
            $cgpa >= 3.6 => 'First Class',
            $cgpa >= 3.0 => 'Second Class Upper',
            $cgpa >= 2.5 => 'Second Class Lower',
            $cgpa >= 2.0 => 'Third Class',
            default => 'Pass',
        };
    }
}