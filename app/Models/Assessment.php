<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperAssessment
 */
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
        'created_by',
    ];

    protected $appends = ['long_title', 'assessment_period_time'];

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

            if ($session->exists) {
                return $session->load('schoolPeriod')->schoolPeriod;
            }
        }

        return null;
    }

    public function getAssessmentPeriodTimeAttribute()
    {
        return $this->getAssessmentPeriodTime();
    }

    public function getLongTitleAttribute(): string
    {
        return $this->longTitle();
    }

    public function isToday(): bool
    {
        return $this->due_date->isToday();
    }

    public function isThisWeek(): bool
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return $this->due_date->between($startOfWeek, $endOfWeek);
    }

    /**
     * Mutate title attribute
     */
    protected function longTitle(): string
    {
        $prefix = $this->load('batchSubject.subject', 'batchSubject.batch.level', 'batchSubject.teacher.user')->batchSubject->subject->full_name.' ';

        $suffix = match (true) {
            $this->isToday() => ' Today'.
            ($this->assessment_period_time) ?
                ' on '.Carbon::createFromDate($this->due_date)->format('M jS').' at '.Carbon::parse($this->assessment_period_time?->start_time)->format('H:i A') :
                ' on '.Carbon::createFromDate($this->due_date)->getTranslatedDayName(),
            $this->isThisWeek() => ' on '.Carbon::createFromDate($this->due_date)->getTranslatedDayName(),
            default => ' on '.$this->due_date->format('F jS'),
        };

        return $prefix.$this->title.$suffix;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    protected $casts = [
        'due_date' => 'datetime',
    ];
}
