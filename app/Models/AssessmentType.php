<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperAssessmentType
 */
class AssessmentType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'percentage',
        'min_assessments',
        'max_assessments',
        'customizable',
        'is_admin_controlled',
        'school_year_id',
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

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
