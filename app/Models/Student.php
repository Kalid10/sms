<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function activeBatch($load = ['level', 'schoolYear'])
    {
        return $this->batches()->whereHas('batch.schoolYear', function ($query) {
            $query->where('end_date', null);
        })->first()->batch->load($load);
    }

    public function activeSession()
    {
        return $this->activeBatch([])->getSessions();
    }

    public function absenteeRecords(int $schoolYearId = null, int $batchSubjectId = null)
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::getActiveSchoolYear()->id;

        return Absentee::where('user_id', $this->user_id)
            ->whereHas('batchSession.batchSchedule', function ($query) use ($schoolYearId) {
                $query->whereIn('batch_id', $this->batches->pluck('batch_id'))
                    ->whereHas('batch', function ($query) use ($schoolYearId) {
                        $query->where('school_year_id', $schoolYearId);
                    });
            })->when($batchSubjectId, function ($query) use ($batchSubjectId) {
                $query->whereHas('batchSession.batchSubject', function ($query) use ($batchSubjectId) {
                    $query->where('batch_subject_id', $batchSubjectId);
                });
            });
    }

    public function absenteePercentage(int $schoolYearId = null, int $batchSubjectId = null): float
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::getActiveSchoolYear()->id;

        // Get the total number of completed batch sessions for the student's batch
        $completedBatchSessions = BatchSession::whereHas('batchSchedule', function ($query) use ($schoolYearId) {
            $query->whereIn('batch_id', $this->batches->pluck('batch_id'))
                ->whereHas('batch', function ($query) use ($schoolYearId) {
                    $query->where('school_year_id', $schoolYearId);
                });
        })->when($batchSubjectId, function ($query) use ($batchSubjectId) {
            $query->whereHas('batchSubject', function ($query) use ($batchSubjectId) {
                $query->where('batch_subject_id', $batchSubjectId);
            });
        })->where('status', BatchSession::STATUS_COMPLETED)->count();

        // Get the total number of absent records for the specific student
        $absenteeRecords = $this->absenteeRecords()->count();

        // Calculate the absentee percentage
        if ($completedBatchSessions === 0) {
            return 0;
        }

        return round(($absenteeRecords / $completedBatchSessions) * 100, 1);
    }

    public function batchSessions(): HasManyThrough
    {
        return $this->activeBatch()->sessions();
    }

    public function upcomingSessions(array $with = [], Teacher $teacher = null): HasManyThrough
    {
        return $this->activeBatch()->sessions()
            ->with($with)
            ->where('status', BatchSession::STATUS_SCHEDULED)
            ->where('date', '>', now())
            ->when($teacher, function ($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            })
            ->orderBy('date', 'asc');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(StudentAssessment::class)
            ->with('assessment.assessmentType', 'assessment.batchSubject.batch.level', 'assessment.batchSubject.subject');
    }

    public function base_batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_students')
            ->withTimestamps();
    }

    public function currentBatch(): BelongsToMany
    {
        return $this->base_batches()->whereHas('schoolYear', function ($query) {
            $query->where('end_date', null);
        });
    }

    public function studentAssessmentsGrades(): HasMany
    {
        return $this->hasMany(StudentAssessmentsGrade::class);
    }

    public function fetchAssessmentsGrade(
        int $batchSubjectId = null,
        int $quarterId = null,
        int $semesterId = null,
        int $schoolYearId = null,
        int $assessmentTypeId = null,
    ): Collection {
        $query = $this->studentAssessmentsGrades();

        $query->when($quarterId, function ($query) use ($quarterId) {
            $query->where('gradable_type', Quarter::class)
                ->where('gradable_id', $quarterId);
        }
        );

        $query->when($assessmentTypeId, function ($query) use ($assessmentTypeId) {
            $query->where('assessment_type_id', $assessmentTypeId);
        }
        );

        $query->when($semesterId, function ($query) use ($semesterId) {
            $query->where('gradable_type', Semester::class)
                ->where('gradable_id', $semesterId);
        }
        );

        $query->when($schoolYearId, function ($query) use ($schoolYearId) {
            $query->where('gradable_type', SchoolYear::class)
                ->where('gradable_id', $schoolYearId);
        }
        );

        $query->when($batchSubjectId, function ($query) use ($batchSubjectId) {
            $query->where('batch_subject_id', $batchSubjectId);
        }
        );

        return $query->with('assessmentType', 'gradeScale')->get();
    }
}
