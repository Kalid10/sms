<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchSession extends Model
{
    const STATUS_SCHEDULED = 'scheduled';

    const STATUS_IN_PROGRESS = 'in_progress';

    const STATUS_COMPLETED = 'completed';

    use HasFactory;

    protected $fillable = [
        'date',
        'batch_schedule_id',
        'teacher_id',
        'status',
    ];

    public function batchSchedule(): BelongsTo
    {
        return $this->belongsTo(BatchSchedule::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Absentee::class);
    }
}
