<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BatchSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_period_id',
        'day_of_week',
        'batch_subject_id',
        'batch_id',
    ];

    public function batchSubject(): BelongsTo
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function schoolPeriod(): BelongsTo
    {
        return $this->belongsTo(SchoolPeriod::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(BatchSession::class);
    }

    public function nextSession(): HasOne
    {
        return $this->hasOne(BatchSession::class)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date');
    }

    public function lastSession(): HasOne
    {
        return $this->hasOne(BatchSession::class)
            ->where('date', '<=', now()->toDateString())
            ->orderBy('date', 'desc');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
