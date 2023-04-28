<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LessonPlan extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'batch_session_ids',
        'topic',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['batch_session_ids', 'topic', 'description'])
            ->useLogName('lesson_plan');
    }

    public function batchSessions(): HasMany
    {
        return $this->hasMany(BatchSession::class);
    }

    protected $casts = ['batch_session_ids' => 'array'];
}