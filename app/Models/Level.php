<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level_category_id',
    ];

    public function levelCategory(): BelongsTo
    {
        return $this->belongsTo(LevelCategory::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function activeBatches(): HasMany
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();

        return $this->batches()
            ->where('school_year_id', $schoolYear->id);
    }
}
