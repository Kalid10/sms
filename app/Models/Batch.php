<?php

namespace App\Models;

use App\Helpers\AbsenteeHelper;
use App\Helpers\BatchSessionHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperBatch
 */
class Batch extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'level_id',
        'school_year_id',
        'section',
        'min_students',
        'max_students',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function homeroomTeacher(): HasOne
    {
        return $this->hasOne(HomeroomTeacher::class);
    }

    public function base_students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'batch_students')
            ->withTimestamps();
    }

    public function students(): HasMany
    {
        return $this->hasMany(BatchStudent::class);
    }

    public function base_subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'batch_subjects', 'batch_id', 'subject_id')
            ->withPivot('teacher_id', 'weekly_frequency')
            ->withTimestamps();
    }

    public function assessments(): HasManyThrough
    {
        return $this->hasManyThrough(Assessment::class, BatchSubject::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(BatchSubject::class);
    }

    public function schedule(): HasMany
    {
        return $this->hasMany(BatchSchedule::class);
    }

    public function sessions(): HasManyThrough
    {
        return $this->hasManyThrough(BatchSession::class, BatchSchedule::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'batch_subjects')
            ->withPivot('subject_id', 'weekly_frequency')
            ->withTimestamps();
    }

    public function inProgressSession(): ?BatchSession
    {
        return $this->sessions->where('status', BatchSession::STATUS_IN_PROGRESS)->first()?->load('absentees.user');
    }

    public function getSessions(bool $force = false, string $span = 'now'): BatchSession|Collection|null
    {
        BatchSessionHelper::sync($this->load('sessions.schoolPeriod'), $force);

        AbsenteeHelper::setAbsenteesFromPreviousSession();

        return match ($span) {
            'now' => $this->inProgressSession(),
            'week' => $this->sessions
                ->where('date', '>=', Carbon::now()->startOfWeek())
                ->where('date', '<=', Carbon::now()->endOfWeek()),
            default => $this->sessions,
        };
    }

    public function weeklySessions(): HasManyThrough
    {
        return $this->sessions()
            ->where('date', '>=', Carbon::now()->startOfWeek())
            ->where('date', '<=', Carbon::now()->endOfWeek());
    }

    public function activeSession(): HasManyThrough
    {
        return $this->sessions()
            ->where('status', BatchSession::STATUS_IN_PROGRESS);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['level_id', 'school_year_id', 'section'])
            ->useLogName('batch');
    }

    public function isActive(): bool
    {
        return $this->school_year_id === SchoolYear::getActiveSchoolYear()->id;
    }

    public static function active(array $with = []): Collection
    {
        return static::with($with)
            ->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->get();
    }

    public function grades(): HasMany
    {
        return $this->hasMany(BatchGrade::class);
    }

    public function totalScheduleSlots(): int
    {
        return SchoolPeriod::where([
            'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
            'level_category_id' => $this->load('level.levelCategory')->level->levelCategory->id,
            'is_custom' => 0,
        ])->count() * 5;
    }

    public function occupiedScheduleSlots(): int
    {
        return $this->loadCount('schedule')->schedule_count;
    }

    public function availableScheduleSlots(): int
    {
        return $this->totalScheduleSlots() - $this->occupiedScheduleSlots();
    }

    protected $casts = [
        'session_last_synced' => 'datetime',
    ];
}
