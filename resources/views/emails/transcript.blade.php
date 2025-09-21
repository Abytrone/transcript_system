<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Official Transcript</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }
        .transcript-info {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school['name'] }}</div>
        <div>{{ $school['address'] }}</div>
        <div>Phone: {{ $school['phone'] }} | Email: {{ $school['email'] }}</div>
    </div>

    <div class="content">
        <h2>Official Transcript Delivery</h2>

        <p>Dear Recipient,</p>

        <p>Please find attached the official transcript for:</p>

        <div class="transcript-info">
            <strong>Student:</strong> {{ $transcript->student->full_name }}<br>
            <strong>Student ID:</strong> {{ $transcript->student->student_id }}<br>
            <strong>Program:</strong> {{ $transcript->program }}<br>
            <strong>Transcript Number:</strong> {{ $transcript->transcript_number }}<br>
            <strong>Year of Completion:</strong> {{ $transcript->year_of_completion }}<br>
            <strong>CGPA:</strong> {{ $transcript->cgpa }}<br>
            <strong>Class of Degree:</strong> {{ $transcript->class_of_degree }}
        </div>

        @if($message)
            <p><strong>Additional Message:</strong></p>
            <p>{{ $message }}</p>
        @endif

        <p>This transcript is official and can be verified using the QR code or transcript number on the document.</p>

        <p>If you have any questions, please contact us at {{ $school['email'] }} or {{ $school['phone'] }}.</p>

        <p>Best regards,<br>
        {{ $school['name'] }}<br>
        Academic Records Office</p>
    </div>

    <div class="footer">
        <p><strong>Important:</strong> This email contains confidential academic information. Please ensure it reaches the intended recipient only.</p>
        <p>© {{ date('Y') }} {{ $school['name'] }}. All rights reserved.</p>
    </div>
</body>
</html>
