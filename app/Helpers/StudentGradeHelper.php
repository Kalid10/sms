<?php

namespace App\Helpers;

use App\Models\Assessment;
use App\Models\GradeScale;
use App\Models\StudentAssessment;
use App\Models\StudentAssessmentsGrade;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentGradeHelper
{
    public static function processGrades(array $student_points, Assessment $assessment): void
    {
        self::insertStudentAssessmentPoints($student_points, $assessment);
    }

    /**
     * @return void
     *
     * Insert or update student points for a given assessment
     */
    private static function insertStudentAssessmentPoints(array $student_points, Assessment $assessment): void
    {
        $student_points = collect($student_points)->map(function ($student_point) use ($assessment) {
            $student_point['assessment_id'] = $assessment->id;

            return $student_point;
        });

        DB::beginTransaction();

        try {
            foreach ($student_points as $student_point) {
                DB::table('student_assessments')->updateOrInsert(
                    [
                        'assessment_id' => $student_point['assessment_id'],
                        'student_id' => $student_point['student_id'],
                    ],
                    [
                        ...$student_point,
                        'updated_at' => now(),
                    ]
                );
            }

            self::updateStudentAssessmentsQuarterGrades($student_points->pluck('student_id'), $assessment);
            self::updateStudentSubjectQuarterGrades($student_points->pluck('student_id'), $assessment);
            self::updateStudentQuarterGrade($student_points->pluck('student_id'), $assessment);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
        }
    }

    /**
     * @return void
     *
     * Recalculate and score students' quarterly
     * class assessment grades for a given assessment
     */
    private static function updateStudentAssessmentsQuarterGrades(Collection $student_ids, Assessment $assessment): void
    {
        $assessmentType = $assessment->load('assessmentType')->assessmentType;

        $student_ids->each(function ($student_id) use ($assessment, $assessmentType) {
            $studentAssessmentPointsByType =
                self::fetchStudentAssessmentsByType($student_id, $assessment)
                    ->with('assessment:maximum_point,id')
                    ->get()
                    ->map(fn ($studentAssessment) => [
                        'point' => $studentAssessment->point,
                        'maximum_point' => $studentAssessment->assessment->maximum_point,
                    ]);

            $studentAssessmentsScore =
                self::calculateStudentAssessmentsScore(
                    $studentAssessmentPointsByType,
                    $assessmentType->percentage
                );

            StudentAssessmentsGrade::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'batch_subject_id' => $assessment->batch_subject_id,
                    'assessment_type_id' => $assessmentType->id,
                ],
                [
                    'student_id' => $student_id,
                    'batch_subject_id' => $assessment->batch_subject_id,
                    'assessment_type_id' => $assessmentType->id,
                    'score' => $studentAssessmentsScore,
                    'grade_scale_id' => GradeScale::get(
                        score: $studentAssessmentsScore,
                        maximum: $assessmentType->percentage
                    )->id,
                    'gradable_type' => 'App\Models\Quarter',
                    'gradable_id' => $assessment->quarter_id,
                ]
            );
        });
    }

    /**
     * @return void
     *
     * Recalculate and score students' quarterly
     * subject grades for a given assessment
     */
    private static function updateStudentSubjectQuarterGrades(Collection $student_ids, Assessment $assessment): void
    {
        $student_ids->each(function ($student_id) use ($assessment) {
            $studentSubjectQuarterGrade = StudentAssessmentsGrade::where([
                'student_id' => $student_id,
                'batch_subject_id' => $assessment->batch_subject_id,
                'gradable_type' => 'App\Models\Quarter',
                'gradable_id' => $assessment->quarter_id,
            ])->sum('score');

            StudentSubjectGrade::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'batch_subject_id' => $assessment->batch_subject_id,
                    'gradable_type' => 'App\Models\Quarter',
                    'gradable_id' => $assessment->quarter_id,
                ],
                [
                    'student_id' => $student_id,
                    'batch_subject_id' => $assessment->batch_subject_id,
                    'gradable_type' => 'App\Models\Quarter',
                    'gradable_id' => $assessment->quarter_id,
                    'score' => $studentSubjectQuarterGrade,
                    'grade_scale_id' => GradeScale::get(
                        score: $studentSubjectQuarterGrade,
                        maximum: 100
                    )->id,
                ]
            );
        });
    }

    /**
     * @return void
     *
     * Recalculate and score students' quarterly
     * grades for a given assessment
     */
    private static function updateStudentQuarterGrade(Collection $student_ids, Assessment $assessment): void
    {
        $student_ids->each(function ($student_id) use ($assessment) {
            $studentQuarterGrade = StudentSubjectGrade::where([
                'student_id' => $student_id,
                'gradable_type' => 'App\Models\Quarter',
                'gradable_id' => $assessment->quarter_id,
            ])->sum('score');

            StudentGrade::updateOrCreate([
                'student_id' => $student_id,
                'gradable_type' => 'App\Models\Quarter',
                'gradable_id' => $assessment->quarter_id,
            ], [
                'student_id' => $student_id,
                'gradable_type' => 'App\Models\Quarter',
                'gradable_id' => $assessment->quarter_id,
                'score' => $studentQuarterGrade,
                'grade_scale_id' => GradeScale::get(
                    score: $studentQuarterGrade,
                    maximum: 100
                )->id,
            ]);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * Query and return a student's assessments
     * for a given assessment type, class, and quarter
     */
    private static function fetchStudentAssessmentsByType(int $student_id, Assessment $assessment): \Illuminate\Database\Eloquent\Collection
    {
        return StudentAssessment::whereNotNull('point')
            ->where('student_id', $student_id)
            ->whereHas('assessment', function ($query) use ($assessment) {
                $query
                    ->where(
                        'assessment_type_id',
                        $assessment->assessment_type_id
                    )
                    ->where('batch_subject_id', $assessment->batch_subject_id)
                    ->where('quarter_id', $assessment->quarter_id);
            });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection  $studentAssessmentPointsByType
     * @return float
     *
     * Calculate and return a student's quarterly
     * assessment score for a given assessment type
     */
    private static function calculateStudentAssessmentsScore(Collection $studentAssessmentPointsByType, int $percentage): float
    {
        $totalPoints = $studentAssessmentPointsByType->sum('point');
        $totalMaximumPoints = $studentAssessmentPointsByType->sum('maximum_point');

        return $totalPoints / $totalMaximumPoints * ($percentage);
    }
}