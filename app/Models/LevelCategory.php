<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class);
    }

    public function schoolPeriods(): HasMany
    {
        return $this->hasMany(SchoolPeriod::class);
    }

    public function getSchoolPeriodsBySchoolYearId(int $schoolYearId): Collection
    {
        return $this->schoolPeriods()
            ->where('school_year_id', $schoolYearId)
            ->get();
    }

    public function activeSchoolPeriods(): null|Collection
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();
        if (! $schoolYear) {
            return null;
        }

        return $this->getSchoolPeriodsBySchoolYearId($schoolYear->id);
    }

    public function assessmentTypes(): HasMany
    {
        return $this->hasMany(AssessmentType::class);
    }
}
