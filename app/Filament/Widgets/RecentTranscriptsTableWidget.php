<?php

namespace App\Filament\Widgets;

use App\Models\Transcript;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTranscriptsTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Transcripts';

    protected static ?int $sort = 11;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transcript::query()
                    ->with(['student', 'issuedBy'])
                    ->where('status', 'issued')
                    ->latest('issued_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('transcript_number')
                    ->label('Transcript #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year_of_completion')
                    ->label('Year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cgpa')
                    ->label('CGPA')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('class_of_degree')
                    ->label('Class')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'First Class' => 'success',
                        'Second Class Upper' => 'primary',
                        'Second Class Lower' => 'warning',
                        'Third Class' => 'danger',
                        'Pass' => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('issuedBy.name')
                    ->label('Issued By'),
                    // Make issue date human readable
                Tables\Columns\TextColumn::make('issued_at')
                    ->label('Issued Date')
                    ->dateTime()
                    ->formatStateUsing(function ($state) {
                        return $state->format('d M Y');
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('issued_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
