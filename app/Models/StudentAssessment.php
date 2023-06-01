<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAssessment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_VALID = 'valid';

    const STATUS_INVALID = 'invalid';

    const STATUS_DISQUALIFIED = 'disqualified';

    const STATUS_MISCONDUCT = 'misconduct';

    protected $fillable = [
        'assessment_id',
        'student_id',
        'point',
        'comment',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
