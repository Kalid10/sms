<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $appends = ['order'];

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

    public function activePeriods(bool $custom = false): ?Collection
    {
        $activeSchoolYear = SchoolYear::getActiveSchoolYear();

        if (! $activeSchoolYear) {
            return null;
        }

        return $this->where('school_year_id', $activeSchoolYear->id)
            ->where('is_custom', $custom)
            ->get();
    }

    public function getActiveSchoolSchedule(): ?SchoolSchedule
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();
        if (! $schoolYear) {
            return null;
        }

        return $this->getSchoolScheduleBySchoolYearId($schoolYear->id);
    }

    public function getOrderAttribute(): int
    {
        $schoolPeriod = $this->load('levelCategory', 'schoolYear');
        $schoolPeriods = SchoolPeriod::where([
            'level_category_id' => $schoolPeriod->level_category_id,
            'school_year_id' => $schoolPeriod->school_year_id,
        ])->pluck('start_time')->map(function ($startTime) {
            return Carbon::today()->setTimeFromTimeString($startTime);
        })->toArray();

        usort($schoolPeriods, function ($a, $b) {
            return $a->gt($b);
        });

        return array_search(Carbon::today()->setTimeFromTimeString($schoolPeriod->start_time), $schoolPeriods) + 1;
    }
}
