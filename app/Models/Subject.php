<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperSubject
 */
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

    public function teachers(string $search = null)
    {
        return BatchSubject::where('subject_id', $this->getKey())
            ->whereIn('batch_id', Batch::active()->pluck('id'))
            ->with('batch', 'batch.level', 'batch.level.levelCategory:id,name', 'teacher', 'teacher.user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('teacher.user', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                });
            })
            ->get()
            ->groupBy('teacher_id')
            ->map(fn ($teacherBatches) => [
                'teacher' => $teacherBatches->first()->teacher ? [
                    'teacher_id' => $teacherBatches->first()->teacher->id,
                    ...$teacherBatches->first()->teacher->user->toArray(),
                ] : null,
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

    public function activeBatches(): BelongsToMany
    {
        return $this->batches()->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
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
