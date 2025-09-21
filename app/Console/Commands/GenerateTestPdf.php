<?php

namespace App\Console\Commands;

use App\Models\Transcript;
use App\Services\PdfService;
use Illuminate\Console\Command;

class GenerateTestPdf extends Command
{
	protected $signature = 'app:generate-test-pdf {id?}';

	protected $description = 'Generate a test transcript PDF for the given transcript ID or the first one.';

	public function handle(PdfService $pdfService): int
	{
		$transcript = $this->argument('id') ? Transcript::find($this->argument('id')) : Transcript::first();
		if (! $transcript) {
			$this->warn('No transcript found.');
			return self::SUCCESS;
		}

		$relative = 'transcripts/test_' . $transcript->id . '.pdf';
		$pdfService->renderViewToPdf('pdf.transcript', [
			'transcript' => $transcript,
		], $relative);

		$this->info('PDF generated at public/storage/' . $relative);
		return self::SUCCESS;
	}
}
