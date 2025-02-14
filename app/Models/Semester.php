<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperSemester
 */
class Semester extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    const STATUS_ACTIVE = 'active';

    const STATUS_COMPLETED = 'completed';

    const STATUS_UPCOMING = 'upcoming';

    protected $fillable = [
        'name',
        'status',
        'start_date',
        'end_date',
        'school_year_id',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function quarters(): HasMany
    {
        return $this->hasMany(Quarter::class);
    }

    public static function getActiveSemester(): ?Semester
    {
        $semester = Semester::firstWhere('end_date', null);

        if (! $semester) {
            return null;
        }

        return $semester;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'start_date', 'end_date', 'school_year_id'])
            ->useLogName('semester');
    }
}
