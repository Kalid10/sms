<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batchSubjects(): HasMany
    {
        return $this->hasMany(BatchSubject::class);
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

    public function lessonPlans(): HasManyThrough
    {
        return $this->hasManyThrough(
            LessonPlan::class,
            BatchSession::class,
            'teacher_id', // Foreign key on BatchSession table
            'batch_session_id' // Foreign key on LessonPlan table
        );
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
}
