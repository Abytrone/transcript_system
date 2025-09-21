<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranscriptRequestResource\Pages;
use App\Models\TranscriptRequest;
use App\Models\Transcript;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
class TranscriptRequestResource extends Resource
{
    protected static ?string $model = TranscriptRequest::class;

    /**
     * Determine class of degree based on CGPA
     */
    public static function determineClassOfDegree(float $cgpa): string
    {
        return match (true) {
            $cgpa >= 3.6 => 'First Class',
            $cgpa >= 3.0 => 'Second Class Upper',
            $cgpa >= 2.0 => 'Second Class Lower',
            $cgpa >= 1.0 => 'Third Class',
            default => 'Pass',
        };
    }

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Transcript Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('📋 Request Information')
                    ->description('Basic request details')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('request_number')
                                    ->label('Request Number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->default('REQ-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)),

                                Forms\Components\Select::make('request_type')
                                    ->label('Request Type')
                                    ->options([
                                        'official' => 'Official Transcript',
                                        'unofficial' => 'Unofficial Transcript',
                                    ])
                                    ->default('official')
                                    ->required()
                                    ->live(),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('👨‍🎓 Student Information')
                    ->description('Student details for transcript request')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Forms\Components\Select::make('student_id')
                            ->label('Student')
                            ->relationship('student', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->student_id} - {$record->full_name}")
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $student = \App\Models\Student::find($state);
                                    if ($student) {
                                        // Auto-populate email if available
                                        $set('recipient_email', $student->email);
                                    }
                                }
                            }),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('📦 Delivery Information')
                    ->description('How the transcript should be delivered')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('delivery_method')
                                    ->label('Delivery Method')
                                    ->options([
                                        'email' => 'Email Delivery',
                                        'pickup' => 'In-Person Pickup',
                                        'mail' => 'Physical Mail',
                                    ])
                                    ->default('email')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state !== 'email') {
                                            $set('recipient_email', null);
                                        }
                                    }),

                                Forms\Components\TextInput::make('recipient_email')
                                    ->label('Recipient Email')
                                    ->email()
                                    ->required(fn ($get) => $get('delivery_method') === 'email')
                                    ->visible(fn ($get) => $get('delivery_method') === 'email')
                                    ->placeholder('Enter recipient email address...')
                                    ->helperText('Required for email delivery'),
                            ]),

                        Forms\Components\Textarea::make('recipient_address')
                            ->label('Recipient Address')
                            ->visible(fn ($get) => $get('delivery_method') === 'mail')
                            ->placeholder('Enter complete mailing address...')
                            ->helperText('Required for physical mail delivery')
                            ->rows(3),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('📝 Additional Information')
                    ->description('Additional notes and remarks')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Forms\Components\Textarea::make('remarks')
                            ->label('Special Instructions')
                            ->placeholder('Any special instructions or notes for this request...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('⚙️ Administrative')
                    ->description('Administrative fields (for staff use)')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Request Status')
                                    ->options([
                                        'pending' => 'Pending Review',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'completed' => 'Completed',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])),

                                Forms\Components\Select::make('handled_by')
                                    ->label('Handled By')
                                    ->relationship('handler', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DateTimePicker::make('approved_at')
                                    ->label('Approved Date')
                                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])),

                                Forms\Components\DateTimePicker::make('rejected_at')
                                    ->label('Rejected Date')
                                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])),

                                Forms\Components\DateTimePicker::make('completed_at')
                                    ->label('Completed Date')
                                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])),
                            ]),
                    ])
                    ->collapsible()
                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->icon('heroicon-o-hashtag')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('request_type')
                    ->label('Type')
                    ->icon('heroicon-o-document-text')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'official' => 'success',
                        'unofficial' => 'info',
                    }),

                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Delivery')
                    ->icon('heroicon-o-truck')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'primary',
                        'pickup' => 'warning',
                        'mail' => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-check-badge',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                    }),

                Tables\Columns\TextColumn::make('handler.name')
                    ->label('Handled By')
                    ->icon('heroicon-o-user-circle')
                    ->placeholder('Not assigned')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('transcript.transcript_number')
                    ->label('Transcript #')
                    ->icon('heroicon-o-document')
                    ->placeholder('Not generated')
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->icon('heroicon-o-calendar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved')
                    ->icon('heroicon-o-check')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->icon('heroicon-o-check-badge')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\SelectFilter::make('request_type')
                    ->options([
                        'official' => 'Official',
                        'unofficial' => 'Unofficial',
                    ]),
                Tables\Filters\SelectFilter::make('delivery_method')
                    ->options([
                        'email' => 'Email',
                        'pickup' => 'Pickup',
                        'mail' => 'Mail',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('generate_transcript')
                        ->label('Generate Transcript')
                        ->icon('heroicon-o-document-plus')
                        ->color('success')
                        ->action(function (TranscriptRequest $record) {
                            // Load the student with program relationship
                            $record->load('student.program');

                            // Create transcript from request
                            $transcript = Transcript::create([
                                'student_id' => $record->student_id,
                                'program' => $record->student->program?->name ?? '',
                                'year_of_completion' => $record->student->year_of_completion,
                                'cgpa' => $record->student->getCumulativeGPA(),
                                'class_of_degree' => self::determineClassOfDegree($record->student->getCumulativeGPA()),
                                'status' => 'draft',
                                'delivery_method' => $record->delivery_method,
                                'recipient_email' => $record->recipient_email,
                                'delivery_notes' => $record->remarks,
                            ]);

                            // Update request with transcript ID and mark as completed
                            $record->update([
                                'transcript_id' => $transcript->id,
                                'status' => 'completed',
                                'completed_at' => now(),
                                'handled_by' => Auth::id(),
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Transcript generated successfully')
                                ->success()
                                ->send();
                        })
                        ->visible(fn (TranscriptRequest $record): bool =>
                            $record->status === 'approved' &&
                            !$record->transcript_id &&
                            Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])
                        ),

                    Tables\Actions\Action::make('approve_request')
                        ->label('Approve Request')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (TranscriptRequest $record) {
                            // Load the student with program relationship
                            $record->load('student.program');

                            // Update request status
                            $record->update([
                                'status' => 'approved',
                                'approved_at' => now(),
                                'handled_by' => Auth::id(),
                            ]);

                            // Generate transcript automatically
                            $transcript = Transcript::create([
                                'student_id' => $record->student_id,
                                'program' => $record->student->program->name ?? 'N/A',
                                'year_of_completion' => $record->student->year_of_completion ?? date('Y'),
                                'cgpa' => $record->student->results()->avg('gpa') ?? 0.0,
                                'class_of_degree' => static::determineClassOfDegree($record->student->results()->avg('gpa') ?? 0.0),
                                'status' => 'issued',
                                'issued_by' => Auth::id(),
                                'issued_at' => now(),
                                'delivery_method' => $record->delivery_method,
                                'recipient_email' => $record->recipient_email,
                                'recipient_address' => $record->recipient_address,
                                'remarks' => "Generated from transcript request #{$record->request_number}",
                            ]);

                            // Link the transcript to the request
                            $record->update(['transcript_id' => $transcript->id]);

                            \Filament\Notifications\Notification::make()
                                ->title('Request Approved & Transcript Generated')
                                ->body("Your transcript request has been approved and transcript #{$transcript->transcript_number} has been generated.")
                                ->success()
                                ->send();
                        })
                        ->visible(fn (TranscriptRequest $record): bool =>
                            $record->status === 'pending' &&
                            Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])
                        ),

                    Tables\Actions\Action::make('reject_request')
                        ->label('Reject Request')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(3)
                                ->placeholder('Please provide a reason for rejection...'),
                        ])
                        ->action(function (TranscriptRequest $record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'rejected_at' => now(),
                                'handled_by' => Auth::id(),
                                'remarks' => $data['rejection_reason'],
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Request rejected')
                                ->warning()
                                ->send();
                        })
                        ->visible(fn (TranscriptRequest $record): bool =>
                            $record->status === 'pending' &&
                            Auth::user()->hasAnyRole(['super_admin', 'faculty_admin'])
                        ),

                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListTranscriptRequests::route('/'),
            'create' => Pages\CreateTranscriptRequest::route('/create'),
            'view' => Pages\ViewTranscriptRequest::route('/{record}'),
            'edit' => Pages\EditTranscriptRequest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}