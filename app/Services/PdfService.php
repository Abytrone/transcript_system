<?php

namespace App\Services;

use App\Models\Transcript;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Symfony\Component\Process\Process;
use RuntimeException;

class PdfService
{
	public function renderViewToPdf(string $view, array $data, string $outputRelativePath): string
	{
		$html = View::make($view, $data)->render();

		$tmpHtml = tempnam(sys_get_temp_dir(), 'pdfhtml_') . '.html';
		file_put_contents($tmpHtml, $html);

		$publicPath = Storage::disk('public')->path('');
		if (! is_dir($publicPath)) {
			mkdir($publicPath, 0775, true);
		}

		$outputPath = Storage::disk('public')->path($outputRelativePath);
		$chrome = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
		if (! file_exists($chrome)) {
			$chrome = trim((string) shell_exec('which google-chrome || which chrome || which chromium || which chromium-browser'));
		}
		if (! $chrome) {
			@unlink($tmpHtml);
			throw new RuntimeException('Chrome/Chromium not found. Please install Google Chrome.');
		}

		$process = new Process([
			$chrome,
			'--headless',
			'--disable-gpu',
			'--no-sandbox',
			'--print-to-pdf=' . $outputPath,
			'file://' . $tmpHtml,
		]);
		$process->setTimeout(60);
		$process->run();

		@unlink($tmpHtml);

		if (! $process->isSuccessful()) {
			throw new RuntimeException('PDF generation failed: ' . $process->getErrorOutput());
		}

		return $outputRelativePath;
	}

	/**
	 * Generate transcript PDF
	 */
	public function generateTranscriptPdf(Transcript $transcript)
	{
		$transcript->load([
			'student.department.faculty',
			'transcriptCourses.course',
			'issuedBy'
		]);

		$data = [
			'transcript' => $transcript,
			'school' => [
				'name' => 'School of Hygiene, Tamale',
				'address' => 'Tamale, Northern Region, Ghana',
				'phone' => '+233 24 123 4567',
				'email' => 'info@schoolofhygiene.edu.gh',
			],
		];

		$html = View::make('pdf.transcript', $data)->render();

		$tmpHtml = tempnam(sys_get_temp_dir(), 'transcript_') . '.html';
		file_put_contents($tmpHtml, $html);

		$chrome = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
		if (!file_exists($chrome)) {
			$chrome = trim((string) shell_exec('which google-chrome || which chrome || which chromium || which chromium-browser'));
		}

		if (!$chrome) {
			@unlink($tmpHtml);
			throw new RuntimeException('Chrome/Chromium not found. Please install Google Chrome.');
		}

		$process = new Process([
			$chrome,
			'--headless',
			'--disable-gpu',
			'--no-sandbox',
			'--print-to-pdf=-',
			'file://' . $tmpHtml,
		]);

		$process->setTimeout(60);
		$process->run();

		@unlink($tmpHtml);

		if (!$process->isSuccessful()) {
			throw new RuntimeException('PDF generation failed: ' . $process->getErrorOutput());
		}

		$pdfContent = $process->getOutput();

		return response($pdfContent, 200, [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="transcript-' . $transcript->transcript_number . '.pdf"',
		]);
	}

	/**
	 * Generate transcript PDF to file
	 */
	public function generateTranscriptPdfToFile(Transcript $transcript, string $outputPath)
	{
		$transcript->load([
			'student.department.faculty',
			'transcriptCourses.course',
			'issuedBy'
		]);

		$data = [
			'transcript' => $transcript,
			'school' => [
				'name' => 'School of Hygiene, Tamale',
				'address' => 'Tamale, Northern Region, Ghana',
				'phone' => '+233 24 123 4567',
				'email' => 'info@schoolofhygiene.edu.gh',
			],
		];

		$html = View::make('pdf.transcript', $data)->render();

		$tmpHtml = tempnam(sys_get_temp_dir(), 'transcript_') . '.html';
		file_put_contents($tmpHtml, $html);

		$chrome = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
		if (!file_exists($chrome)) {
			$chrome = trim((string) shell_exec('which google-chrome || which chrome || which chromium || which chromium-browser'));
		}

		if (!$chrome) {
			@unlink($tmpHtml);
			throw new RuntimeException('Chrome/Chromium not found. Please install Google Chrome.');
		}

		$process = new Process([
			$chrome,
			'--headless',
			'--disable-gpu',
			'--no-sandbox',
			'--print-to-pdf=' . $outputPath,
			'file://' . $tmpHtml,
		]);

		$process->setTimeout(60);
		$process->run();

		@unlink($tmpHtml);

		if (!$process->isSuccessful()) {
			throw new RuntimeException('PDF generation failed: ' . $process->getErrorOutput());
		}
	}

}
