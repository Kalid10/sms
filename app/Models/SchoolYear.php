<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SchoolYear extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'start_date',
        'end_date',
        'name',
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
}
