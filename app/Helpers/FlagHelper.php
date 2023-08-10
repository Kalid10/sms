<?php

namespace App\Helpers;

use App\Models\Assessment;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;
use Carbon\Carbon;

class FlagHelper
{
    public static function processStudentFlags($batchSubjectId): void
    {
        if (! $batchSubjectId) {
            return;
        }
        $batchSubject = BatchSubject::find($batchSubjectId);
        $studentIds = $batchSubject->students()->get()->pluck('id');

        $batchSubjectAssessment = $batchSubject->assessments()->where('status', Assessment::STATUS_COMPLETED)->get();

        if ($batchSubjectAssessment->groupBy('assessment_type_id')->count() > 1 && $batchSubjectAssessment->count() > 2) {
            self::checkSubjectStudents($batchSubject, $studentIds);
            self::checkStudentsGrade($studentIds);
        }
    }

    private static function checkSubjectStudents($batchSubject, $studentIds): void
    {
        $studentSubjectGrades = StudentSubjectGrade::where([
            'gradable_type' => Quarter::class,
            'gradable_id' => Quarter::getActiveQuarter()->id,
            'batch_subject_id' => $batchSubject->id,
        ])->whereIn('student_id', $studentIds)->with('gradeScale')->get();

        $studentSubjectGrades->each(function ($studentSubjectGrade) {
            if ($studentSubjectGrade->gradeScale->label === 'F' || $studentSubjectGrade->gradeScale->label === 'D') {
                StudentHelper::flagStudent(
                    $studentSubjectGrade->student_id,
                    'academic',
                    'This student is in danger of failing, requiring immediate attention and focused support.
                It is crucial that we provide intensive interventions and closely monitor their progress to ensure their academic success.',
                    null,
                    $studentSubjectGrade->batch_subject_id,
                    Carbon::now()->addWeek(),
                );
            }
        });
    }

    private static function checkStudentsGrade($studentIds): void
    {
        $studentGrades = StudentGrade::where(
            ['gradable_type' => Quarter::class,
                'gradable_id' => Quarter::getActiveQuarter()->id,

            ])->whereIn('student_id', $studentIds)->with('gradeScale')->get();

        $studentGrades->each(function ($studentGrade) {
            if ($studentGrade->gradeScale->label === 'F' || $studentGrade->gradeScale->label === 'D') {
                StudentHelper::flagStudent(
                    $studentGrade->student_id,
                    'academic',
                    'This student is in danger of failing, requiring immediate attention and focused support.
                It is crucial that we provide intensive interventions and closely monitor their progress to ensure their academic success.',
                    null,
                    null,
                    Carbon::now()->addWeek(),
                );
            }
        });
    }
}
