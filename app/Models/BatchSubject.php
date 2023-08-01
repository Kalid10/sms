<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function schoolYear(): HasOneThrough
    {
        return $this->hasOneThrough(
            SchoolYear::class,
            Batch::class,
            'id', // Foreign key on Batch table
            'id',
            'batch_id', // Foreign key on SchoolYear table
            'school_year_id'
        );
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

    public function nextSession(): HasOneThrough
    {
        return $this->hasOneThrough(
            BatchSession::class,
            BatchSchedule::class,
            'batch_subject_id', // Foreign key on BatchSchedule table
            'batch_schedule_id'
        )->where('date', '>=', now()->toDateString())
            ->orderBy('date');
    }

    public function lastSession(): HasOneThrough
    {
        return $this->hasOneThrough(
            BatchSession::class,
            BatchSchedule::class,
            'batch_subject_id', // Foreign key on BatchSchedule table
            'batch_schedule_id'
        )->where('date', '<=', now()->toDateString())
            ->orderBy('date', 'desc');
    }

    public function level(): HasOneThrough
    {
        return $this->hasOneThrough(
            Level::class,
            Batch::class,
            'id', // Foreign key on Batch table
            'id',
            'batch_id', // Foreign key on Level table
            'level_id'
        );
    }

    public static function active(array $with = []): Collection
    {
        return static::with($with)->whereIn('batch_id', Batch::active()->pluck('id')->toArray())->get();
    }

    public function isActive(): bool
    {
        return $this->batch->school_year_id === SchoolYear::getActiveSchoolYear()->id;
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            BatchStudent::class,
            'batch_id', // Foreign key on BatchStudent table
            'id', // Foreign key on Student table
            'batch_id', // Local key on BatchSubject table
            'student_id' // Local key on BatchStudent table
        );
    }

    public function studentGrades(): HasMany
    {
        return $this->hasMany(StudentSubjectGrade::class);
    }

    public function batchGrades(): HasMany
    {
        return $this->hasMany(BatchSubjectGrade::class);
    }

    public function assessmentsGrades(): HasMany
    {
        return $this->hasMany(StudentAssessmentsGrade::class, 'batch_subject_id', 'id');
    }

    public function flag(): BelongsTo
    {
        return $this->belongsTo(Flag::class);
    }
}
