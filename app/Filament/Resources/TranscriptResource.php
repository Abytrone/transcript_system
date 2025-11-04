<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranscriptResource\Pages;
use App\Models\Transcript;
use App\Models\Student;
use App\Services\PdfService;
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

        $user = Auth::user();

        if ($user->hasRole('lecturer')) {
            return $query->whereHas('student', function ($q) use ($user) {
                $q->where('program_id', $user->program_id)
                    ->orWhereHas('results', function ($q2) use ($user) {
                        $q2->whereHas('course.lecturers', function ($q3) use ($user) {
                            $q3->where('users.id', $user->id);
                        });
                    });
            });
        }

        if ($user->hasRole('department_admin')) {
            return $query->whereHas('student', function ($q) use ($user) {
                $q->where('department_id', $user->department_id);
            });
        }

        if ($user->hasRole('faculty_admin')) {
            return $query->whereHas('student.department', function ($q) use ($user) {
                $q->where('faculty_id', $user->faculty_id);
            });
        }

        return $query;
    }

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
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'first_name',
                        modifyQueryUsing: function (Builder $query) {
                            $user = Auth::user();

                            if ($user->hasRole('lecturer')) {
                                return $query->where(function ($q) use ($user) {
                                    $q->where('program_id', $user->program_id)
                                        ->orWhereHas('results', function ($q2) use ($user) {
                                            $q2->whereHas('course.lecturers', function ($q3) use ($user) {
                                                $q3->where('users.id', $user->id);
                                            });
                                        });
                                });
                            }

                            if ($user->hasRole('department_admin')) {
                                return $query->where('department_id', $user->department_id);
                            }

                            if ($user->hasRole('faculty_admin')) {
                                return $query->whereHas('department', function ($q) use ($user) {
                                    $q->where('faculty_id', $user->faculty_id);
                                });
                            }

                            return $query;
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn (Student $record): string => "{$record->student_id} - {$record->full_name}")
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $student = Student::with(['program', 'department'])->find($state);
                            if ($student) {
                                $set('program', $student->program?->name ?? '');
                                $set('year_of_completion', $student->year_of_completion);
                                $cgpa = $student->getCumulativeGPA();
                                $set('cgpa', $cgpa);
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
                    ->visible(fn (): bool => Auth::user()->hasAnyRole(['super_admin', 'faculty_admin', 'department_admin']))
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if ($state === 'issued') {
                            $set('issued_by', Auth::id());
                            $set('issued_at', today()->format('Y-m-d'));
                        }
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
                    ->dehydrated()
                    ->disabled()
                    ->visible(fn ($get) => $get('status') === 'issued'),

                Forms\Components\TextInput::make('issued_at')
                    ->label('Issued Date')
                    ->visible(fn ($get) => $get('status') === 'issued')
                    ->default(today()->format('Y-m-d'))
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return today()->format('Y-m-d');
                        }
                        // If it's a datetime string, extract just the date part
                        if (is_string($state)) {
                            return substr($state, 0, 10); // Get YYYY-MM-DD from YYYY-MM-DDTHH:mm:ssZ
                        }
                        // If it's a Carbon/DateTime object, format it
                        if ($state instanceof \DateTimeInterface) {
                            return $state->format('Y-m-d');
                        }
                        return $state;
                    })
                    ->dehydrated()
                    ->disabled(),

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
                    ->color(function (string $state): string {
                        $cgpa = (float) $state;
                        if ($cgpa >= 3.6) {
                            return 'success';
                        }
                        if ($cgpa >= 3.0) {
                            return 'primary';
                        }
                        if ($cgpa >= 2.5) {
                            return 'warning';
                        }
                        if ($cgpa >= 2.0) {
                            return 'danger';
                        }
                        return 'gray';
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
                                        // ignore errors per-record
                                    }
                                }
                            }
                            Notification::make()
                                ->title("Generated {$count} PDFs successfully")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

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
