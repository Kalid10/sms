<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assessment_type_id',
        'batch_subject_id',
        'quarter_id',
        'due_date',
        'title',
        'description',
        'maximum_point',
    ];

    public function assessmentType(): BelongsTo
    {
        return $this->belongsTo(AssessmentType::class);
    }

    public function batchSubject(): BelongsTo
    {
        return $this->belongsTo(BatchSubject::class);
    }

    public function quarter(): BelongsTo
    {
        return $this->belongsTo(Quarter::class);
    }

    public function teacher(): HasOneThrough
    {
        return $this->hasOneThrough(
            Teacher::class,
            BatchSubject::class,
            'teacher_id',
            'id',
            'batch_subject_id',
            'teacher_id'
        );
    }

    protected $casts = [
        'due_date' => 'datetime',
    ];
}