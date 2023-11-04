<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperStudentTuitionPenalty
 */
class StudentTuitionPenalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_tuition_id',
        'penalty_id',
        'amount',
    ];

    public function studentTuition(): BelongsTo
    {
        return $this->belongsTo(StudentTuition::class);
    }
}
