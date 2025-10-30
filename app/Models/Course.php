<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'credits',
        'department_id',
        'program_id',
        'level', // 100, 200, 300, 400, etc.
        'semester', // 1, 2
        'status', // active, inactive
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function transcriptCourses(): HasMany
    {
        return $this->hasMany(TranscriptCourse::class);
    }

    public function lecturers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user');
    }
}
