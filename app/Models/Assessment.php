<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_DRAFT = 'draft';

    const STATUS_SCHEDULED = 'scheduled';

    const STATUS_PUBLISHED = 'published';

    const STATUS_CLOSED = 'closed';

    const STATUS_MARKING = 'marking';

    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'assessment_type_id',
        'batch_subject_id',
        'quarter_id',
        'due_date',
        'title',
        'description',
        'maximum_point',
        'lesson_plan_id',
        'status',
    ];

    protected $appends = ['assessment_period_time'];

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

    // TODO: get all the assessments of a batch subject and/or an assessment type
    public static function filter(
        BatchSubject $batchSubject = null,
        AssessmentType $assessmentType = null,
        Quarter $quarter = null
    ): Builder {
        return self::when($batchSubject, function ($query) use ($batchSubject) {
            return $query->where('batch_subject_id', $batchSubject->id);
        })
            ->when($assessmentType, function ($query) use ($assessmentType) {
                return $query->where('assessment_type_id', $assessmentType->id);
            })
            ->when($quarter, function ($query) use ($quarter) {
                return $query->where('quarter_id', $quarter->id);
            });
    }

    public function students(): HasMany
    {
        return $this->hasMany(StudentAssessment::class);
    }

    public function getAssessmentPeriodTime()
    {
        // Get the date of the assessment
        $assessmentDate = $this->due_date;

        // Query the BatchSchedule
        $batchSchedule = BatchSchedule::where('batch_subject_id', $this->batch_subject_id)
            ->whereHas('sessions', function ($query) use ($assessmentDate) {
                $query->whereDate('date', $assessmentDate);
            })
            ->first();

        if ($batchSchedule) {
            $session = $batchSchedule->sessions()
                ->whereDate('date', $assessmentDate)
                ->first();

            if ($session) {
                $schoolPeriod = $session->schoolPeriod;

                return $schoolPeriod;
            }
        }

        return null;
    }

    public function getAssessmentPeriodTimeAttribute()
    {
        return $this->getAssessmentPeriodTime();
    }

    protected $casts = [
        'due_date' => 'datetime',
    ];
}
