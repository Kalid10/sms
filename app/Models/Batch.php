<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'level_id',
        'school_year_id',
        'section',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function homeroomTeacher(): HasOne
    {
        return $this->hasOne(HomeroomTeacher::class);
    }
}
