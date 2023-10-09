<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperQuarter
 */
class Quarter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'semester_id',
    ];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function schoolYear(): HasOneThrough
    {
        return $this->hasOneThrough(
            SchoolYear::class,
            Semester::class,
            'id', // Foreign key on Semester table
            'id', // Foreign key on SchoolYear table
            'semester_id', // Local key on Quarter table
            'school_year_id' // Local key on Semester table
        );
    }

    public static function getActiveQuarter(): ?Quarter
    {
        $quarter = Quarter::firstWhere('end_date', null);

        if (! $quarter) {
            // No active quarter found
            return null;
        }

        return $quarter;
    }
}
