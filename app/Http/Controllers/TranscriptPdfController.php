<?php

namespace App\Http\Controllers;

use App\Models\Transcript;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class TranscriptPdfController extends Controller
{
	public function generate(Transcript $transcript, PdfService $pdfService): RedirectResponse
	{
		$relative = 'transcripts/' . $transcript->id . '.pdf';
		$pdfService->renderViewToPdf('pdf.transcript', [
			'transcript' => $transcript,
		], $relative);

		$transcript->update([
			'pdf_path' => 'storage/' . $relative,
		]);

		return back()->with('status', 'PDF generated.');
	}
}
