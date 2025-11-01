<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'department_id',
        'program_id',
        'year_of_admission',
        'year_of_completion',
        'status', // active, graduated, dropped
        'photo_path',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'year_of_admission' => 'integer',
        'year_of_completion' => 'integer',
    ];

    public function getYearOfCompletionAttribute($value): ?int
    {
        if (! is_null($value)) {
            return (int) $value;
        }

        $admission = $this->year_of_admission;
        $duration = optional($this->program)->duration_years;

        if (is_numeric($admission) && is_numeric($duration)) {
            return (int) $admission + (int) $duration;
        }

        return null;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function program(): BelongsToRelation
    {
        return $this->belongsTo(Program::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, Result::class);
    }

    public function studentCourses(): HasMany
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    public function getStudentIdAttribute($value): string
    {
        return strtoupper($value);
    }

    public function getResultsBySemester($academicYear = null)
    {
        $query = $this->results()->with('course');

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        return $query->orderBy('academic_year')
                    ->orderBy('semester')
                    ->get()
                    ->groupBy(['academic_year', 'semester']);
    }

    public function getCumulativeGPA()
    {
        $results = $this->results()->whereNotNull('gpa')->get();

        if ($results->isEmpty()) {
            return 0.0;
        }

        return round($results->avg('gpa'), 2);
    }

    public function getSemesterGPA($academicYear, $semester)
    {
        $semesterResults = $this->results()
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->whereNotNull('gpa')
            ->get();

        if ($semesterResults->isEmpty()) {
            return 0.0;
        }

        return round($semesterResults->avg('gpa'), 2);
    }

    public function getTotalCourses()
    {
        return $this->results()->count();
    }

    public function getTranscriptData()
    {
        return [
            'student' => $this,
            'results_by_semester' => $this->getResultsBySemester(),
            'cumulative_gpa' => $this->getCumulativeGPA(),
            'total_courses' => $this->getTotalCourses(),
        ];
    }

}
