<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TranscriptPdfController;
use App\Http\Controllers\TranscriptVerificationController;
use App\Http\Controllers\StudentAccessController;
use App\Http\Controllers\StudentTranscriptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/admin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['web', 'auth'])->group(function () {
	Route::post('/transcripts/{transcript}/pdf', [TranscriptPdfController::class, 'generate'])->name('transcripts.pdf');
	Route::get('/student/{student}/transcript', [StudentTranscriptController::class, 'generateTranscript'])->name('student.transcript');
});

// Public routes for transcript verification and student access
Route::prefix('verify')->name('transcript.')->group(function () {
    Route::get('/{uuid}', [TranscriptVerificationController::class, 'show'])->name('verify');
    Route::post('/{uuid}', [TranscriptVerificationController::class, 'verify'])->name('verify.api');
    Route::get('/{uuid}/details', [TranscriptVerificationController::class, 'details'])->name('details');
});

Route::prefix('student-access')->name('transcript.')->group(function () {
    Route::get('/{uuid}', [StudentAccessController::class, 'show'])->name('student-access');
    Route::get('/{uuid}/data', [StudentAccessController::class, 'getTranscript'])->name('student-access.data');
    Route::get('/{uuid}/download', [StudentAccessController::class, 'download'])->name('student-access.download');
});

require __DIR__.'/auth.php';