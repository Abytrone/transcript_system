<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'score',
        'grade',
        'gpa',
        'is_resit',
        'academic_year',
        'semester',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'score' => 'integer',
        'is_resit' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getGradeBadgeColorAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'success',
            'B+', 'B' => 'primary',
            'C+', 'C' => 'info',
            'D+', 'D' => 'warning',
            'F' => 'danger',
            default => 'secondary',
        };
    }

    public function getScorePercentageAttribute(): string
    {
        return $this->score ? $this->score . '%' : 'N/A';
    }
}
