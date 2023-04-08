<?php

namespace App\Models;

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
        'level_categories_id',
    ];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function levelCategory(): BelongsTo
    {
        return $this->belongsTo(LevelCategory::class);
    }
}
