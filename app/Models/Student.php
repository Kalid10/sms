<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guardian_id',
        'guardian_relation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }

    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_students');
    }

    public function activeBatch($load = [])
    {
        return $this->load('batches')->batches
            ->filter(function ($batch) {
                return $batch->school_year_id === SchoolYear::getActiveSchoolYear()->id;
            })
        ->first()->load($load);
    }

    public function activeSession()
    {
        return $this->activeBatch([])->getSessions();
    }

    public function absenteeRecords(int $schoolYearId = null)
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::getActiveSchoolYear()->id;

        return Absentee::where('user_id', $this->user_id)
            ->whereHas('batchSession.batchSchedule', function ($query) use ($schoolYearId) {
                $query->whereIn('batch_id', $this->batches->pluck('batch_id'))
                    ->whereHas('batch', function ($query) use ($schoolYearId) {
                        $query->where('school_year_id', $schoolYearId);
                    });
            });
    }

    public function absenteePercentage(int $schoolYearId = null): float
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::getActiveSchoolYear()->id;

        // Get the total number of completed batch sessions for the student's batch
        $completedBatchSessions = BatchSession::whereHas('batchSchedule', function ($query) use ($schoolYearId) {
            $query->whereIn('batch_id', $this->batches->pluck('batch_id'))
                ->whereHas('batch', function ($query) use ($schoolYearId) {
                    $query->where('school_year_id', $schoolYearId);
                });
        })->where('status', BatchSession::STATUS_COMPLETED)->count();

        // Get the total number of absent records for the specific student
        $absenteeRecords = $this->absenteeRecords()->count();

        // Calculate the absentee percentage
        if ($completedBatchSessions === 0) {
            return 0;
        }

        return ($absenteeRecords / $completedBatchSessions) * 100;
    }
}
