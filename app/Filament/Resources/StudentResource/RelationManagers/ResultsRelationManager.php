<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    protected static ?string $title = 'Academic Results';

    protected static ?string $modelLabel = 'Result';

    protected static ?string $pluralModelLabel = 'Results';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(function () {
                        if (!Auth::check()) {
                            return [];
                        }
                        $user = Auth::user();
                        if ($user->hasRole('lecturer')) {
                            return $user->taughtCourses()->orderBy('code')->get()->pluck('code', 'id')->map(function ($code, $id) {
                                $course = Course::find($id);
                                return $course ? ($course->code . ' - ' . $course->title) : $code;
                            })->toArray();
                        }
                        return [];
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->placeholder('Enter exam score (0-100)'),
                Forms\Components\Select::make('grade')
                    ->options([
                        'A' => 'A',
                        'B+' => 'B+',
                        'B' => 'B',
                        'C+' => 'C+',
                        'C' => 'C',
                        'D+' => 'D+',
                        'D' => 'D',
                        'F' => 'F',
                    ])
                    ->nullable(),
                Forms\Components\TextInput::make('gpa')
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->maxValue(4.0)
                    ->placeholder('Enter GPA (0.00-4.00)'),
                Forms\Components\Toggle::make('is_resit')
                    ->label('Is Resit')
                    ->default(false),
                Forms\Components\TextInput::make('academic_year')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., 2020/2021'),
                Forms\Components\Select::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('course.title')
            ->columns([
                Tables\Columns\TextColumn::make('course.code')
                    ->label('Course Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course Title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('score')
                    ->label('Score')
                    ->suffix('%')
                    ->numeric()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('grade')
                    ->badge()
                    ->colors([
                        'success' => 'A',
                        'primary' => 'B+',
                        'info' => 'B',
                        'warning' => 'C+',
                        'secondary' => 'C',
                        'slate' => 'D+',
                        'gray' => 'D',
                        'danger' => 'F',
                    ])
                    ->placeholder('No Grade'),
                Tables\Columns\TextColumn::make('gpa')
                    ->numeric()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\IconColumn::make('is_resit')
                    ->boolean()
                    ->label('Resit')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('slate')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function (Tables\Columns\IconColumn $column): ?string {
                        $state = $column->getState();
                        if ($state) {
                            return 'Resit';
                        }
                        return 'Not Resit';
                    }),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->badge()
                    ->colors([
                        'primary' => 1,
                        'secondary' => 2,
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_year')
                    ->options(function () {
                        return $this->getOwnerRecord()
                            ->results()
                            ->distinct()
                            ->pluck('academic_year', 'academic_year')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ]),
                Tables\Filters\SelectFilter::make('grade')
                    ->options([
                        'A' => 'A',
                        'B+' => 'B+',
                        'B' => 'B',
                        'C+' => 'C+',
                        'C' => 'C',
                        'D+' => 'D+',
                        'D' => 'D',
                        'F' => 'F',
                    ]),
                Tables\Filters\TernaryFilter::make('is_resit')
                    ->label('Resit Only'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => Auth::check() && Auth::user()->hasRole('lecturer')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->visible(fn () => Auth::check() && Auth::user()->hasRole('lecturer')),
                Tables\Actions\DeleteAction::make()->visible(fn () => Auth::check() && Auth::user()->hasRole('lecturer')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn () => Auth::check() && Auth::user()->hasRole('lecturer')),
                ]),
            ])
            ->defaultSort('academic_year', 'desc')
            ->groups([
                Tables\Grouping\Group::make('academic_year')
                    ->label('Academic Year')
                    ->collapsible(),
                Tables\Grouping\Group::make('semester')
                    ->label('Semester')
                    ->collapsible(),
            ]);
    }
}
