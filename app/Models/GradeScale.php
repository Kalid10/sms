<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperGradeScale
 */
class GradeScale extends Model
{
    use HasFactory;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function pass(): bool
    {
        return $this->state === 'pass';
    }

    public function fail(): bool
    {
        return $this->state === 'fail';
    }

    public static function get(float $score, float $maximum = 100.00, int $school_year_id = null): GradeScale
    {
        $school_year_id = $school_year_id ?? SchoolYear::getActiveSchoolYear()->id;

        return GradeScale::where('school_year_id', $school_year_id)
            ->where('minimum_score', '<=', $score / $maximum * 100)
            ->orderBy('minimum_score', 'desc')->first();
    }
}
