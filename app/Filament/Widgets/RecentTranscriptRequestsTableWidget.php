<?php

namespace App\Filament\Widgets;

use App\Models\TranscriptRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTranscriptRequestsTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Transcript Requests';

    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TranscriptRequest::query()
                    ->with(['student', 'handler'])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('request_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'official' => 'success',
                        'unofficial' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Delivery')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pickup' => 'secondary',
                        'email' => 'primary',
                        'mail' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('handler.name')
                    ->label('Handled By')
                    ->placeholder('Not assigned'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
