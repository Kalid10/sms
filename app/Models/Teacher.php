<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leave_info',
    ];

    protected $appends = ['active_weekly_sessions'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batchSubjects(): HasMany
    {
        return $this->hasMany(BatchSubject::class);
    }

    public function activeBatchSubjects(): HasMany
    {
        return $this->hasMany(BatchSubject::class)
            ->whereIn('batch_id', Batch::active()->pluck('id'));
    }

    public function activeBatches(): HasManyThrough
    {
        return $this->hasManyThrough(
            Batch::class,
            BatchSubject::class,
            'teacher_id', // Foreign key on BatchSubject table
            'id', // Foreign key on Batch table
            'id', // Local key on Teacher table
            'batch_id' // Local key on BatchSubject table
        );
    }

    public function batchSchedules(): HasManyThrough
    {
        return $this->hasManyThrough(
            BatchSchedule::class,
            BatchSubject::class,
            'teacher_id', // Foreign key on BatchSubject table
            'batch_subject_id' // Foreign key on BatchSchedule table
        );
    }

    public function activeWeeklySessions(): int
    {
        return $this->load('activeBatchSubjects')->activeBatchSubjects->sum('weekly_frequency');
    }

    public function batchSessions(): HasMany
    {
        return $this->hasMany(BatchSession::class);
    }

    public function homeroom(): HasMany
    {
        return $this->hasMany(HomeroomTeacher::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(TeacherFeedback::class);
    }

    public function assessments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Assessment::class,
            BatchSubject::class,
            'teacher_id', // Foreign key on BatchSession table
            'batch_subject_id' // Foreign key on Assessment table
        );
    }

    public function nextBatchSession(): HasOne
    {
        return $this->hasOne(BatchSession::class)
            ->where('status', BatchSession::STATUS_SCHEDULED)
            ->whereDate('date', '>', now())
            ->orderBy('date', 'asc');
    }

    public function inProgressBatchSession(): HasOne
    {
        return $this->hasOne(BatchSession::class)
            ->where('status', BatchSession::STATUS_IN_PROGRESS);
    }

    public function lessonPlans(): HasMany
    {
        return $this->hasMany(BatchSession::class, 'teacher_id', 'id')->with('lessonPlan')->whereHas('lessonPlan');
    }

    public function getActiveWeeklySessionsAttribute(): int
    {
        return $this->activeWeeklySessions();
    }

    public function staffAbsentee(): HasMany
    {
        return $this->hasMany(StaffAbsentee::class);
    }

    protected $casts = [
        'leave_info' => 'array',
    ];
}
