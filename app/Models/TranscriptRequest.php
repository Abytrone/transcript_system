<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranscriptRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'request_type', // official, unofficial
        'delivery_method', // email, pickup, mail
        'recipient_email',
        'recipient_address',
        'status', // pending, approved, rejected, completed
        'remarks',
        'handled_by',
        'approved_at',
        'rejected_at',
        'completed_at',
        'transcript_id',
        'request_number',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function transcript(): BelongsTo
    {
        return $this->belongsTo(Transcript::class);
    }
}
