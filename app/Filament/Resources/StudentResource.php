<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\Department;
use App\Models\Program;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Student Management';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->hasRole('lecturer')) {
            return $query->whereHas('program', function ($q) use ($user) {
                $q->where('id', $user->program_id);
            })->orWhereHas('results', function ($q) use ($user) {
                $q->whereHas('course.lecturers', function ($q2) use ($user) {
                    $q2->where('users.id', $user->id);
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_id')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('nationality')
                    ->default('Ghanaian')
                    ->maxLength(255),
                Forms\Components\Textarea::make('address'),
                Forms\Components\Select::make('program_id')
                    ->label('Program')
                    ->relationship(
                        name: 'program',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $user = Auth::user();

                            if ($user->hasRole('lecturer')) {
                                return $query->where('id', $user->program_id);
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
                    ->required()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($state, $set) {
                        $program = Program::find($state);
                        if ($program) {
                            $set('department_id', $program->department_id);
                        }
                    }),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('year_of_admission')
                    ->options(function () {
                        $currentYear = date('Y');
                        $years = [];
                        for ($year = 2000; $year <= $currentYear; $year++) {
                            $years[$year] = $year;
                        }
                        return $years;
                    })
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('year_of_completion')
                    ->options(function () {
                        $currentYear = date('Y');
                        $years = ['' => 'Not Completed'];
                        for ($year = 2000; $year <= $currentYear + 5; $year++) {
                            $years[$year] = $year;
                        }
                        return $years;
                    })
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'graduated' => 'Graduated',
                        'dropped' => 'Dropped',
                    ])
                    ->default('active')
                    ->required(),
                Forms\Components\FileUpload::make('photo_path')
                    ->image()
                    ->directory('student-photos'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=' . urlencode('Student') . '&background=random')
                    ->url(fn ($record) => $record->photo_path),
                Tables\Columns\TextColumn::make('student_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Full Name')
                    ->formatStateUsing(fn (Student $record): string => $record->full_name)
                    ->searchable(['first_name', 'last_name', 'middle_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('department.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year_of_admission')
                    ->sortable(),
                Tables\Columns\TextColumn::make('year_of_completion')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'graduated' => 'warning',
                        'dropped' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'graduated' => 'Graduated',
                        'dropped' => 'Dropped',
                    ]),
                Tables\Filters\Filter::make('year_of_admission')
                    ->form([
                        Forms\Components\TextInput::make('year_from')
                            ->numeric()
                            ->placeholder('From year'),
                        Forms\Components\TextInput::make('year_to')
                            ->numeric()
                            ->placeholder('To year'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['year_from'],
                                fn (Builder $query, $year): Builder => $query->where('year_of_admission', '>=', $year),
                            )
                            ->when(
                                $data['year_to'],
                                fn (Builder $query, $year): Builder => $query->where('year_of_admission', '<=', $year),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export Students')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new StudentsExport, 'students-' . date('Y-m-d') . '.xlsx');
                    }),
                Tables\Actions\Action::make('import')
                    ->label('Import Students')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('Excel/CSV File')
                            ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                            ->required()
                            ->helperText('Upload an Excel (.xlsx) or CSV file with student data. Download the template for the correct format.'),
                    ])
                    ->action(function (array $data) {
                        try {
                            Excel::import(new StudentsImport, $data['file']);

                            \Filament\Notifications\Notification::make()
                                ->title('Import Successful')
                                ->body('Students have been imported successfully.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Import Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('import_results')
                    ->label('Import Results')
                    ->icon('heroicon-o-academic-cap')
                    ->color('warning')
                    ->visible(fn () => Auth::check() && Auth::user()->hasRole('lecturer'))
                    ->url(fn () => route('filament.admin.resources.students.import-results'))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ResultsRelationManager::class,
            RelationManagers\StudentCoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'import-results' => Pages\ImportResults::route('/import-results'),
        ];
    }
}
