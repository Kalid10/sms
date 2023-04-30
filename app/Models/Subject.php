<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subject extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'full_name',
        'short_name',
        'category',
        'tags',
        'priority',
    ];

    public function teachers()
    {
        return BatchSubject::where('subject_id', $this->getKey())
            ->whereIn('batch_id', Batch::active()->pluck('id'))
            ->with('batch', 'batch.level', 'batch.level.levelCategory:id,name', 'teacher', 'teacher.user')
            ->get()
            ->groupBy('teacher_id')
            ->map(fn ($teacherBatches) => [
                'teacher' => [
                    'teacher_id' => $teacherBatches->first()->teacher->id,
                    ...$teacherBatches->first()->teacher->user->toArray(),
                ],
                'batches' => $teacherBatches->map(fn ($teacherBatch) => [
                    'batch_id' => $teacherBatch->batch_id,
                    ...$teacherBatch->batch->level->toArray(),
                    'section' => $teacherBatch->batch->section,
                    'level_category' => $teacherBatch->batch->level->levelCategory->name,
                ]),
            ])->values();
    }

    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_subjects')
            ->withPivot('teacher_id', 'weekly_frequency')
            ->withTimestamps();
    }

    public function activeBatches(): Collection
    {
        return $this->batches->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
    }

    protected $casts = [
        'tags' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['full_name', 'short_name'])
            ->useLogName('subject');
    }
}
