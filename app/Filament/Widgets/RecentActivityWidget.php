<?php

namespace App\Filament\Widgets;

use App\Models\TranscriptRequest;
use App\Models\VerificationLog;
use App\Models\Transcript;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Activity';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TranscriptRequest::query()
                    ->with(['student', 'handler'])
                    ->latest()
                    ->limit(10)
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

                Tables\Columns\TextColumn::make('request_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'official' => 'success',
                        'unofficial' => 'info',
                    }),

                Tables\Columns\TextColumn::make('delivery_method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'primary',
                        'pickup' => 'warning',
                        'mail' => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                    }),

                Tables\Columns\TextColumn::make('handler.name')
                    ->label('Handled By')
                    ->placeholder('Not assigned'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (TranscriptRequest $record): string => route('filament.admin.resources.transcript-requests.view', $record)),
            ]);
    }
}
