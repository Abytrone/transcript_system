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

class StudentCoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'studentCourses';

    protected static ?string $title = 'Course Records';

    protected static ?string $modelLabel = 'Course Record';

    protected static ?string $pluralModelLabel = 'Course Records';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->code . ' - ' . $record->title)
                    ->required()
                    ->searchable()
                    ->preload(),
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
                    ->nullable(),
                Forms\Components\TextInput::make('credit_hours')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(6),
                Forms\Components\Select::make('status')
                    ->options([
                        'enrolled' => 'Enrolled',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'resit' => 'Resit',
                    ])
                    ->default('enrolled')
                    ->required(),
                Forms\Components\Toggle::make('is_resit')
                    ->label('Is Resit')
                    ->default(false),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->badge()
                    ->colors([
                        'primary' => 1,
                        'secondary' => 2,
                    ]),
                Tables\Columns\TextColumn::make('grade')
                    ->badge()
                    ->colors([
                        'success' => 'A',
                        'primary' => 'B+',
                        'info' => 'B',
                        'warning' => 'C+',
                        'secondary' => 'C',
                        'danger' => 'D+',
                        'gray' => 'D',
                        'dark' => 'F',
                    ])
                    ->placeholder('No Grade'),
                Tables\Columns\TextColumn::make('gpa')
                    ->numeric()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('credit_hours')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enrolled' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'resit' => 'info',
                        default => 'gray',
                    }),
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
                Tables\Columns\TextColumn::make('remarks')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_year')
                    ->options(function () {
                        return $this->getOwnerRecord()
                            ->studentCourses()
                            ->distinct()
                            ->pluck('academic_year', 'academic_year')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'enrolled' => 'Enrolled',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'resit' => 'Resit',
                    ]),
                Tables\Filters\TernaryFilter::make('is_resit')
                    ->label('Resit Only'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
