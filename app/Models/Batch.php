<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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

    public function students(): HasMany
    {
        return $this->hasMany(BatchStudent::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(BatchSubject::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['level_id', 'school_year_id', 'section'])
            ->useLogName('batch');
    }

    public function batchSchedules(): HasMany
    {
        return $this->hasMany(BatchSchedule::class);
    }

    public function batchSubjects(): HasMany
    {
        return $this->hasMany(BatchSubject::class);
    }

    public static function active(array $with = []): Collection
    {
        return static::with($with)
            ->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->get();
    }
}
