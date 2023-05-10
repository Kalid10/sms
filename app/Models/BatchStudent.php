<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BatchStudent extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'batch_id',
        'student_id',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear(): HasOneThrough
    {
        return $this->hasOneThrough(
            SchoolYear::class,
            Batch::class,
            'id',
            'id',
            'batch_id',
            'school_year_id'
        );
    }

    public function level(): HasOneThrough
    {
        return $this->hasOneThrough(
            Level::class,
            Batch::class,
            'id',
            'id',
            'batch_id',
            'level_id'
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['batch_id', 'student_id'])
            ->useLogName('batch_student');
    }
}
