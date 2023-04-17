<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BatchSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'subject_id',
        'teacher_id',
        'weekly_frequency',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schedule(): HasMany
    {
        return $this->hasMany(BatchSchedule::class);
    }

    public function sessions(): HasManyThrough
    {
        return $this->hasOneThrough(
            BatchSession::class,
            BatchSchedule::class,
            'batch_subject_id', // Foreign key on BatchSchedule table
            'batch_schedule_id'
        );
    }

    public static function active(array $with = []): Collection
    {
        return static::with($with)->whereIn('batch_id', Batch::active()->pluck('id')->toArray())->get();
    }
}
