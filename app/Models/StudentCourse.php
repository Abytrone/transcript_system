<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'academic_year',
        'semester',
        'grade',
        'gpa',
        'credit_hours',
        'status',
        'is_resit',
        'remarks',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
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

    public function getGradePointsAttribute(): float
    {
        $gradePoints = [
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D+' => 1.5,
            'D' => 1.0,
            'F' => 0.0,
        ];

        return $gradePoints[$this->grade] ?? 0.0;
    }

    public function getQualityPointsAttribute(): float
    {
        return $this->grade_points * $this->credit_hours;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'enrolled' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'resit' => 'info',
            default => 'secondary',
        };
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
}
