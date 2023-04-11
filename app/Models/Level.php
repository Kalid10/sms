<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
