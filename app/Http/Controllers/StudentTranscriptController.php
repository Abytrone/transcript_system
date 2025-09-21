<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentTranscriptController extends Controller
{
    public function generateTranscript(Student $student)
    {
        $transcriptData = $student->getTranscriptData();

        $pdf = Pdf::loadView('pdf.transcript', $transcriptData)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->stream("transcript_{$student->student_id}.pdf");
    }
}
