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

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }
}
