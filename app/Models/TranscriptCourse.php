<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranscriptCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'transcript_id',
        'course_id',
        'grade',
        'credit_hours',
        'semester',
        'academic_year',
        'gpa',
    ];

    protected $casts = [
        'credit_hours' => 'integer',
        'gpa' => 'decimal:2',
    ];

    public function transcript(): BelongsTo
    {
        return $this->belongsTo(Transcript::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
