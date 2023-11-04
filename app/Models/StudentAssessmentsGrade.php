<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperStudentAssessmentsGrade
 */
class StudentAssessmentsGrade extends Model
{
    use HasFactory;

    protected $table = 'student_assessments_grades';

    protected $guarded = [
        'updated_at',
        'created_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function assessmentType(): BelongsTo
    {
        return $this->belongsTo(AssessmentType::class);
    }

    public function gradeScale(): BelongsTo
    {
        return $this->belongsTo(GradeScale::class);
    }

    public function batchSubject(): BelongsTo
    {
        return $this->belongsTo(BatchSubject::class);
    }
}
