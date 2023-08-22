<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
        'lesson_plan_id',
        'quarter_id',
    ];

    public function batchSchedule(): BelongsTo
    {
        return $this->belongsTo(BatchSchedule::class);
    }

    public function schoolPeriod(): HasOneThrough
    {
        return $this->hasOneThrough(
            SchoolPeriod::class,
            BatchSchedule::class,
            'id', // Foreign key on BatchSchedule table
            'id', // Foreign key on SchoolPeriod table
            'batch_schedule_id', // Local key on BatchSession table
            'school_period_id' // Local key on BatchSchedule table
        );
    }

    // Get batchSubject through batchSchedule using hasOneThrough relationship
    public function batchSubject(): HasOneThrough
    {
        return $this->hasOneThrough(
            BatchSubject::class,
            BatchSchedule::class,
            'id', // Foreign key on BatchSchedule table
            'id', // Foreign key on BatchSubject table
            'batch_schedule_id', // Local key on BatchSession table
            'batch_subject_id' // Local key on BatchSchedule table
        );
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function absentees(): HasMany
    {
        return $this->hasMany(Absentee::class);
    }

    public function lessonPlan(): BelongsTo
    {
        return $this->belongsTo(LessonPlan::class);
    }

    public function aiNotes(): HasMany
    {
        return $this->hasMany(AINote::class);
    }

    protected $casts = [
        'date' => 'date',
    ];
}
