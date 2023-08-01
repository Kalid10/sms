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

    public function lessonPlans(): HasMany
    {
        return $this->hasMany(BatchSession::class, 'teacher_id', 'id')->with('lessonPlan')->whereHas('lessonPlan');
    }

    public function staffAbsenteeRecords(int $schoolYearId = null, int $batchSubjectId = null)
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::getActiveSchoolYear()->id;

        return StaffAbsentee::where('user_id', $this->user_id)
            ->whereHas('batchSession.batchSchedule', function ($query) use ($schoolYearId) {
                $batches = $this->batches ? $this->batches->pluck('batch_id') : null;

                if ($batches) {
                    $query->whereIn('batch_id', $batches);
                }

                $query->whereHas('batch', function ($query) use ($schoolYearId) {
                    $query->where('school_year_id', $schoolYearId);
                });
            })->when($batchSubjectId, function ($query) use ($batchSubjectId) {
                $query->whereHas('batchSession.batchSchedule', function ($query) use ($batchSubjectId) {
                    $query->where('batch_subject_id', $batchSubjectId);
                });
            });
    }

    public function staffAbsenteePercentage(): float|int
    {
        $absenteeRecords = $this->staffAbsenteeRecords();
        $absenteeCount = $absenteeRecords ? $absenteeRecords->count() : 0;
        $sessionCount = $this->batchSessions()->count();

        return $sessionCount ? round(($absenteeCount / $sessionCount) * 100, 2) : 0;
    }

    public function getActiveWeeklySessionsAttribute(): int
    {
        return $this->activeWeeklySessions();
    }
}
