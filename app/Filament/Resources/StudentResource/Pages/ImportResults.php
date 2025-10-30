<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\ResultsImport;
use App\Exports\ResultsTemplateExport;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ImportResults extends Page
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.import-results';

    protected static ?string $navigationLabel = 'Import Results';

    protected static ?string $title = 'Import Academic Results';

    public ?array $data = [];

    public function mount(): void
    {
        if (!Auth::check() || !Auth::user()->hasRole('lecturer')) {
            Notification::make()
                ->title('Access denied')
                ->body('Only lecturers can access the results import page.')
                ->danger()
                ->send();

            $this->redirect(static::getResource()::getUrl('index'));
            return;
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('📊 Import Academic Results')
                    ->description('Upload Excel/CSV file containing student academic results')
                    ->schema([
                        Forms\Components\FileUpload::make('file')
                            ->label('Results File')
                            ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                            ->required()
                            ->helperText('Upload an Excel (.xlsx) or CSV file with academic results data.')
                            ->columnSpanFull(),

                        Forms\Components\ViewField::make('format_info')
                            ->label('Required Format')
                            ->view('filament.forms.components.results-format-info')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        if (!Auth::check() || !Auth::user()->hasRole('lecturer')) {
            Notification::make()
                ->title('Access denied')
                ->body('Only lecturers can import results.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();

        if (!$data['file']) {
            Notification::make()
                ->title('No file selected')
                ->body('Please select a file to import.')
                ->danger()
                ->send();
            return;
        }

        try {
            Excel::import(new ResultsImport, $data['file']);

            Notification::make()
                ->title('Import Successful')
                ->body('Academic results have been imported successfully.')
                ->success()
                ->send();

            // Clear the form
            $this->form->fill();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import Failed')
                ->body('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_template')
                ->label('Download Template')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->action(function () {
                    return Excel::download(new ResultsTemplateExport, 'results-template-' . date('Y-m-d') . '.xlsx');
                }),
            Actions\Action::make('import')
                ->label('Import Results')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->action('import'),
        ];
    }
}
