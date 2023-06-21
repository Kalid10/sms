<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flag extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function flagable(): MorphTo
    {
        return $this->morphTo();
    }

    public function flaggedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'flagged_by');
    }

    public function batchSubject(): HasOne
    {
        return $this->hasOne(BatchSubject::class, 'id', 'batch_subject_id');
    }
}
