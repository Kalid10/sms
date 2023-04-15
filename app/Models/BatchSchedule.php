<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_period_id',
        'day_of_week',
        'batch_subject_id',
    ];

    public function batchSubject(): BelongsTo
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function schoolPeriod(): BelongsTo
    {
        return $this->belongsTo(SchoolPeriod::class);
    }
}
