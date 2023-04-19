<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function batches(): HasMany
    {
        return $this->hasMany(BatchStudent::class);
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
        $absenteeRecords = Absentee::where('user_id', $this->user_id)
            ->whereHas('batchSession.batchSchedule', function ($query) use ($schoolYearId) {
                $query->whereIn('batch_id', $this->batches->pluck('batch_id'))
                    ->whereHas('batch', function ($query) use ($schoolYearId) {
                        $query->where('school_year_id', $schoolYearId);
                    });
            })->count();

        // Calculate the absentee percentage
        if ($completedBatchSessions === 0) {
            return 0;
        }

        return ($absenteeRecords / $completedBatchSessions) * 100;
    }
}
