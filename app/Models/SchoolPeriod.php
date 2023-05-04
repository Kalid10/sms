<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'duration',
        'is_custom',
        'level_category_id',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function levelCategory(): BelongsTo
    {
        return $this->belongsTo(LevelCategory::class);
    }

    // get active school periods for the active school year
    public function getSchoolPeriodsBySchoolYearId(int $schoolYearId): array
    {
        return $this->where('school_year_id', $schoolYearId)
            ->get()->toArray();
    }

    public function getActiveAllPeriods(): array
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();
        if (! $schoolYear) {
            return [];
        }

        return $this->getSchoolPeriodsBySchoolYearId($schoolYear->id);
    }

    public function activePeriods(bool $custom = false): null|Collection
    {
        $activeSchoolYear = SchoolYear::getActiveSchoolYear();

        if (! $activeSchoolYear) {
            return null;
        }

        return $this->where('school_year_id', $activeSchoolYear->id)
            ->where('is_custom', $custom)
            ->get();
    }

    protected $casts = [
        'start_time' => 'datetime',
    ];
}
