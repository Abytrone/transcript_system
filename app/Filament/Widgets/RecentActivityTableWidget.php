<?php

namespace App\Filament\Widgets;

use App\Models\Transcript;
use App\Models\TranscriptRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecentActivityTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent System Activity';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Activity Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'transcript' => 'success',
                        'request' => 'warning',
                        'verification' => 'info',
                        'statement' => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user')
                    ->label('User/Student')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'issued' => 'success',
                        'pending' => 'warning',
                        'completed' => 'success',
                        'draft' => 'secondary',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        // This is a complex query that combines multiple tables
        // For simplicity, we'll use a union approach
        $transcripts = Transcript::select([
            'id',
            'created_at',
            'status',
            'transcript_number as identifier',
            DB::raw("'transcript' as type"),
            DB::raw("CONCAT('Transcript issued: ', transcript_number) as description"),
            DB::raw("COALESCE(issuedBy.name, 'System') as user")
        ])
        ->leftJoin('users as issuedBy', 'transcripts.issued_by', '=', 'issuedBy.id')
        ->where('status', 'issued');

        $requests = TranscriptRequest::select([
            'id',
            'created_at',
            'status',
            'request_number as identifier',
            DB::raw("'request' as type"),
            DB::raw("CONCAT('Request submitted: ', request_number) as description"),
            DB::raw("COALESCE(handler.name, 'System') as user")
        ])
        ->leftJoin('users as handler', 'transcript_requests.handled_by', '=', 'handler.id');


        // Combine all queries
        return $transcripts
            ->union($requests)
            ->orderBy('created_at', 'desc')
            ->limit(20);
    }
}
