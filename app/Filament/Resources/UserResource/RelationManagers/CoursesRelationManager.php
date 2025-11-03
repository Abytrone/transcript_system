<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Course;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'taughtCourses';

    protected static ?string $title = 'Assigned Courses';

    protected static ?string $modelLabel = 'Course';

    protected static ?string $pluralModelLabel = 'Courses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(function () {
                        // Get courses from the lecturer's department/program
                        $user = $this->getOwnerRecord();
                        $query = Course::query();

                        if ($user->department_id) {
                            $query->where('department_id', $user->department_id);
                        }

                        if ($user->program_id) {
                            $query->where('program_id', $user->program_id);
                        }

                        return $query->orderBy('code')
                            ->get()
                            ->pluck('code', 'id')
                            ->map(function ($code, $id) {
                                $course = Course::find($id);
                                return $course ? ($course->code . ' - ' . $course->title) : $code;
                            });
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Course Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Course Title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('credits')
                    ->label('Credits')
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ((int)$state) {
                        100 => 'success',
                        200 => 'warning',
                        300 => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        100 => 'Level 100',
                        200 => 'Level 200',
                        300 => 'Level 300',
                    ]),
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        // Only show courses from lecturer's department/program
                        $user = $this->getOwnerRecord();

                        if ($user->department_id) {
                            $query->where('department_id', $user->department_id);
                        }

                        if ($user->program_id) {
                            $query->where('program_id', $user->program_id);
                        }

                        return $query;
                    })
                    ->recordTitle(fn ($record) => $record->code . ' - ' . $record->title),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('code');
    }

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        // Only show for lecturers
        return $ownerRecord->hasRole('lecturer');
    }
}

