@php
  $usingTranscriptModel = isset($transcript);
  $schoolMeta = $school ?? [
    'name' => 'School of Hygiene, Tamale',
    'address' => 'Tamale, Northern Region, Ghana',
    'phone' => '+233 24 123 4567',
    'email' => 'info@schoolofhygiene.edu.gh',
  ];

  if ($usingTranscriptModel) {
    $studentModel = $transcript->student;
    $studentName = trim(($studentModel->first_name ?? '') . ' ' . ($studentModel->middle_name ?? '') . ' ' . ($studentModel->last_name ?? ''));
    $studentId = $studentModel->student_id ?? '';
    $programName = $transcript->program ?? optional($studentModel->program)->name;
    $yearOfCompletion = $transcript->year_of_completion ?? $studentModel->year_of_completion ?? null;
    $cgpa = $transcript->cgpa ?? null;
    $classOfDegree = $transcript->class_of_degree ?? null;
    $verificationUrl = isset($transcript->uuid) ? route('transcript.verify', ['uuid' => $transcript->uuid]) : null;
    // Build grouped results from transcript courses if available
    $grouped = collect($transcript->transcriptCourses ?? [])->groupBy(function ($tc) {
      return ($tc->academic_year ?? '');
    })->map(function ($byYear) {
      return $byYear->groupBy(function ($tc) {
        return ($tc->semester ?? '');
      });
    });
  } else {
    // Using Student::getTranscriptData() shape
    $studentModel = $student ?? null;
    $studentName = optional($studentModel)->full_name ?? trim((optional($studentModel)->first_name ?? '') . ' ' . (optional($studentModel)->middle_name ?? '') . ' ' . (optional($studentModel)->last_name ?? ''));
    $studentId = optional($studentModel)->student_id ?? '';
    $programName = optional(optional($studentModel)->program)->name;
    $yearOfCompletion = optional($studentModel)->year_of_completion ?? null;
    $cgpa = $cumulative_gpa ?? null;
    $classOfDegree = null;
    $verificationUrl = null; // No transcript uuid when using raw student data
    $grouped = collect($results_by_semester ?? []);
  }

  function computeClassFromCgpa($cgpa) {
    if ($cgpa === null || $cgpa === '') return null;
    $v = (float) $cgpa;
    if ($v >= 3.60) return 'First Class';
    if ($v >= 3.00) return 'Second Class (Upper Division)';
    if ($v >= 2.00) return 'Second Class (Lower Division)';
    if ($v >= 1.50) return 'Third Class';
    if ($v >= 1.00) return 'Pass';
    return 'Fail';
  }

  $computedClass = $classOfDegree ?? computeClassFromCgpa($cgpa);

  function gradeColor($score) {
    if ($score === null || $score === '') return '#444';
    if ($score >= 70) return '#14532d';
    if ($score >= 60) return '#065f46';
    if ($score >= 50) return '#1e3a8a';
    if ($score >= 45) return '#92400e';
    return '#991b1b';
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Official Transcript</title>
  <style>
    @page { margin: 28mm 18mm; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111827; font-size: 12px; }
    .header { text-align: center; margin-bottom: 16px; }
    .brand { font-size: 22px; letter-spacing: 0.5px; font-weight: 700; color: #0f172a; }
    .muted { color: #6b7280; }
    .subtle { color: #374151; }
    .meta { margin-top: 4px; font-size: 11px; }
    .divider { height: 3px; background: linear-gradient(90deg, #0ea5e9, #22c55e, #a855f7); border-radius: 3px; margin: 10px 0 18px; }
    .section-title { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #0f766e; margin: 18px 0 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; }
    .grid { width: 100%; border-collapse: collapse; }
    .grid th { background: #f8fafc; text-align: left; padding: 8px; font-size: 11px; color: #334155; border-bottom: 1px solid #e5e7eb; }
    .grid td { padding: 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
    .grid .num { text-align: right; white-space: nowrap; }
    .chip { display: inline-block; padding: 2px 8px; border-radius: 999px; background: #eef2ff; color: #3730a3; font-size: 10px; }
    .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 14px; }
    .row { width: 100%; }
    .col-6 { width: 49%; display: inline-block; vertical-align: top; }
    .col-7 { width: 58%; display: inline-block; vertical-align: top; }
    .col-5 { width: 40%; display: inline-block; vertical-align: top; }
    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 16px; }
    .small { font-size: 11px; }
    .qr-box { text-align: center; border: 1px dashed #cbd5e1; padding: 8px; border-radius: 6px; }
    .signature-line { height: 1px; background: #cbd5e1; margin-top: 24px; }
    .footer { position: fixed; left: 0; right: 0; bottom: 18mm; text-align: center; color: #6b7280; font-size: 10px; }
    .watermark { position: fixed; left: 50%; top: 45%; transform: translate(-50%, -50%); opacity: 0.05; font-size: 150px; font-weight: 800; color: #111827; white-space: nowrap; }
  </style>
</head>
<body>
  <div class="watermark">OFFICIAL</div>
  <div class="header">
    <div class="brand">{{ $schoolMeta['name'] }}</div>
    <div class="meta muted">{{ $schoolMeta['address'] }}</div>
    <div class="meta muted">Tel: {{ $schoolMeta['phone'] }} • Email: {{ $schoolMeta['email'] }}</div>
  </div>
  <div class="divider"></div>

  <div class="row mb-3">
    <div class="col-7">
      <div class="section-title">Student Information</div>
      <div class="card">
        <div class="mb-2"><strong>Name:</strong> {{ $studentName }}</div>
        <div class="mb-2"><strong>Student ID:</strong> {{ $studentId }}</div>
        <div class="mb-2"><strong>Programme:</strong> {{ $programName ?? '—' }}</div>
        <div class="mb-2"><strong>Department:</strong> {{ optional(optional($studentModel)->department)->name ?? '—' }}</div>
        <div class="mb-2"><strong>Year of Completion:</strong> {{ $yearOfCompletion ?? '—' }}</div>
      </div>
    </div>
    <div class="col-5">
      <div class="section-title">Transcript Summary</div>
      <div class="card">
        <div class="mb-2"><strong>CGPA:</strong> <span class="chip">{{ $cgpa !== null ? number_format((float) $cgpa, 2) : '—' }}</span></div>
        <div class="mb-2"><strong>Class:</strong> {{ $computedClass ?? '—' }}</div>
        @if($usingTranscriptModel)
          <div class="mb-2"><strong>Transcript No.:</strong> {{ $transcript->transcript_number ?? '—' }}</div>
          <div class="mb-2"><strong>Status:</strong> {{ ucfirst($transcript->status ?? 'draft') }}</div>
          <div class="mb-2"><strong>Issued:</strong> {{ optional($transcript->issued_at)->format('M d, Y') ?? '—' }}</div>
        @else
          <div class="mb-2"><strong>Total Courses:</strong> {{ $total_courses ?? '—' }}</div>
        @endif
      </div>
    </div>
  </div>

  @foreach($grouped as $year => $bySemester)
    @php $yearLabel = $year ?: 'Academic Year'; @endphp
    <div class="section-title">{{ $yearLabel }}</div>
    @foreach($bySemester as $semester => $rows)
      @php $semLabel = is_numeric($semester) ? 'Semester ' . $semester : ($semester ?: 'Semester'); @endphp
      <div class="mb-2 subtle"><strong>{{ $semLabel }}</strong></div>
      <table class="grid mb-4" width="100%">
        <thead>
          <tr>
            <th style="width: 16%">Course Code</th>
            <th style="width: 44%">Course Title</th>
            <th style="width: 10%" class="num">Credits</th>
            <th style="width: 10%" class="num">Score</th>
            <th style="width: 10%" class="num">Grade</th>
            <th style="width: 10%" class="num">GPA</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $row)
            @php
              // Support both Result and TranscriptCourse shapes
              $course = $row->course ?? null;
              $code = $course->code ?? ($row->course_code ?? '');
              $title = $course->title ?? ($row->course_title ?? '');
              $credits = $course->credits ?? ($row->credits ?? $row->credit_hours ?? null);
              $score = $row->score ?? ($row->total_score ?? null);
              $grade = $row->grade ?? null;
              $gpaVal = $row->gpa ?? null;
            @endphp
            <tr>
              <td>{{ $code }}</td>
              <td>{{ $title }}</td>
              <td class="num">{{ $credits !== null ? number_format((float) $credits, 1) : '—' }}</td>
              <td class="num" style="color: {{ gradeColor(is_numeric($score)? (float)$score : null) }}">{{ $score !== null ? number_format((float) $score, 0) : '—' }}</td>
              <td class="num">{{ $grade ?? '—' }}</td>
              <td class="num">{{ $gpaVal !== null ? number_format((float) $gpaVal, 2) : '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      @if(!$usingTranscriptModel)
        <div class="small muted mb-4">Semester GPA: {{ number_format((float) (optional($studentModel)->getSemesterGPA($year, $semester) ?? 0), 2) }}</div>
      @endif
    @endforeach
  @endforeach

  <div class="row mb-4">
    <div class="col-6">
      <div class="section-title">Verification</div>
      <div class="card">
        @if($verificationUrl)
          <div class="mb-2">Scan the QR or visit the link to verify this document.</div>
          <div class="row">
            <div class="col-6">
              <div class="qr-box">
                <div class="small muted mb-2">Verification QR</div>
                {{-- Display stored SVG if available --}}
                @php
                  $qrPath = 'storage/qr-codes/transcript-' . ($transcript->uuid ?? 'x') . '.svg';
                @endphp
                @if(file_exists(public_path($qrPath)))
                  <img src="{{ public_path($qrPath) }}" alt="QR" style="width: 120px; height: 120px;" />
                @else
                  <div class="small muted">QR unavailable</div>
                @endif
              </div>
            </div>
            <div class="col-6">
              <div class="small"><strong>URL:</strong><br>{{ $verificationUrl }}</div>
              <div class="small muted">Verify authenticity online</div>
            </div>
          </div>
        @else
          <div class="small muted">This PDF was generated from in-progress records. Official verification will be available on issuance.</div>
        @endif
      </div>
    </div>
    <div class="col-6">
      <div class="section-title">Authorization</div>
      <div class="card">
        <div class="mb-3 small muted">This transcript is valid only with authorized signatures and institutional seal.</div>
        <div class="row">
          <div class="col-6">
            <div class="signature-line"></div>
            <div class="small">Registrar</div>
            @if($usingTranscriptModel && $transcript->issuedBy)
              <div class="small muted">Issued by: {{ $transcript->issuedBy->name }}</div>
            @endif
          </div>
          <div class="col-6">
            <div class="signature-line"></div>
            <div class="small">Head of Department</div>
            <div class="small muted">{{ optional(optional($studentModel)->department)->name }}</div>
          </div>
        </div>
        @if($usingTranscriptModel && $transcript->issued_at)
          <div class="small muted" style="margin-top: 10px;">Date: {{ $transcript->issued_at->format('M d, Y') }}</div>
        @endif
      </div>
    </div>
  </div>

  <div class="footer">
    {{ $schoolMeta['name'] }} • Official Transcript • Generated {{ now()->format('M d, Y') }}
  </div>
</body>
</html>

