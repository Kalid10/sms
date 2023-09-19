<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';

    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'description',
        'amount',
        'penalty_id',
        'feeable_type',
        'feeable_id',
        'target_user_type',
        'status',
        'due_date',
        'level_category_id',
        'is_student_tuition_fee',
    ];

    public function penalty(): BelongsTo
    {
        return $this->belongsTo(Penalty::class);
    }

    public function levelCategory(): BelongsTo
    {
        return $this->belongsTo(LevelCategory::class);
    }

    public function studentsTuition(): HasMany
    {
        return $this->hasMany(StudentTuition::class);
    }

    public function feeable(): MorphTo
    {
        return $this->morphTo();
    }
}
