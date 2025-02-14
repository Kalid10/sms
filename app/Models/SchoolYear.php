<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperSchoolYear
 */
class SchoolYear extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'start_date',
        'end_date',
        'name',
        'is_ready',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['start_date', 'end_date', 'name'])
            ->useLogName('school_year');
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    public function quarters(): HasManyThrough
    {
        return $this->hasManyThrough(Quarter::class, Semester::class);
    }

    public function schoolPeriods(): HasMany
    {
        return $this->hasMany(SchoolPeriod::class);
    }

    public static function getActiveSchoolYear(): ?SchoolYear
    {
        $schoolYear = SchoolYear::firstWhere('end_date', null);

        if (! $schoolYear) {
            // No active school year found
            return null;
        }

        return $schoolYear;
    }

    public static function isSchoolYearActive(int $schoolId): bool
    {
        // Check if there's an active school year with the given school_id
        return SchoolYear::where('school_id', $schoolId)->whereNull('end_date')->exists();
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function assessmentTypes(): HasMany
    {
        return $this->hasMany(AssessmentType::class);
    }

    public function schoolSchedules(): HasMany
    {
        return $this->hasMany(SchoolSchedule::class);
    }

    // Active school year school schedules
    public function activeSchoolSchedules(): HasMany
    {
        return $this->hasMany(SchoolSchedule::class)->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
    }
}
