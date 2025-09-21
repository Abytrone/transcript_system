<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Services\TranscriptNumberService;

class Transcript extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'transcript_number',
        'student_id',
        'program',
        'year_of_completion',
        'cgpa',
        'class_of_degree',
        'qr_code',
        'status', // draft, issued, verified
        'issued_at',
        'issued_by',
        'verified_at',
        'verified_by',
        'recipient_email',
        'email_sent_at',
        'delivery_method',
        'delivery_notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'verified_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'year_of_completion' => 'integer',
        'cgpa' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function transcriptCourses(): HasMany
    {
        return $this->hasMany(TranscriptCourse::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(TranscriptRequest::class);
    }


    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transcript) {
            if (empty($transcript->uuid)) {
                $transcript->uuid = Str::uuid();
            }

            if (empty($transcript->transcript_number)) {
                $transcriptNumberService = app(TranscriptNumberService::class);
                $transcript->transcript_number = $transcriptNumberService->generateTranscriptNumber();
            }
        });
    }
}
