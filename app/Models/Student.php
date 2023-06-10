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

    public function absenteePercentage(int $batchSubjectId = null): float
    {
        $studentAttendance = $this->studentSubjectGrades()
            ->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->when($batchSubjectId, function ($query) use ($batchSubjectId) {
                $query->where('batch_subject_id', $batchSubjectId);
            })->first()?->attendance;

        return isset($studentAttendance) ? 100 - $studentAttendance : 0;
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

    public function assessmentsGrades(): HasMany
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
        $query = $this->assessmentsGrades();

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

    public function studentSubjectGrades(): HasMany
    {
        return $this->hasMany(StudentSubjectGrade::class);
    }

    public function fetchStudentBatchSubjectGrade(int $batchSubjectId = null, int $quarterId = null, int $semesterId = null): Collection
    {
        return $this->studentSubjectGrades()->where('batch_subject_id', $batchSubjectId)
            ->when($quarterId, function ($query) use ($quarterId) {
                $query->where('gradable_type', Quarter::class)
                    ->where('gradable_id', $quarterId);
            }
            )
            ->when($semesterId, function ($query) use ($semesterId) {
                $query->where('gradable_type', Semester::class)
                    ->where('gradable_id', $semesterId);
            }
            )
            ->with('gradeScale')->get();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(StudentNote::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'batch_subjects',
            'batch_id',
            'subject_id',
            'id',
            'id'
        );
    }
}
