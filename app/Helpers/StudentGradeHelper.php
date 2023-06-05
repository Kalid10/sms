<?php

namespace App\Helpers;

use App\Events\MarkAssessmentEvent;
use App\Models\Assessment;
use App\Models\GradeScale;
use App\Models\Quarter;
use App\Models\Semester;
use App\Models\StudentAssessment;
use App\Models\StudentAssessmentsGrade;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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
            self::updateStudentSemesterGrades($student_points->pluck('student_id'));
            self::updateRank($student_points->pluck('student_id'));

            // TODO:: Test it out very well
            BatchStatisticsHelper::processBatchGrades($assessment->batchSubject);

            $assessment->update([
                'status' => Assessment::STATUS_COMPLETED,
            ]);
            $assessment->save();

            event(new MarkAssessmentEvent('Assessment marked successfully!', 'success'));

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
        $assessmentType = $assessment->load('assessmentType')->assessmentType;
        $student_ids->each(function ($student_id) use ($assessment, $assessmentType) {
            $studentAssessmentGrade = StudentAssessmentsGrade::where([
                'student_id' => $student_id,
                'batch_subject_id' => $assessment->batch_subject_id,
                'gradable_type' => 'App\Models\Quarter',
                'gradable_id' => $assessment->quarter_id,
            ]);
            $studentAssessmentGradeSum = $studentAssessmentGrade->sum('score');

            $studentGradedAssessmentTypes = $studentAssessmentGrade->distinct('assessment_type_id')->pluck('assessment_type_id');
            $studentTotalGradedPercentage = $studentGradedAssessmentTypes->map(function ($assessment_type_id) use ($assessmentType) {
                return (int) $assessmentType->where('id', $assessment_type_id)->first()->percentage;
            })->sum();

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
                    'score' => $studentAssessmentGradeSum,
                    'grade_scale_id' => GradeScale::get(
                        score: $studentAssessmentGradeSum,
                        maximum: $studentTotalGradedPercentage
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
     * @return void
     *
     * Recalculate and score students' semester
     * grades for a given assessment
     */
    private static function updateStudentSemesterGrades(Collection $student_ids): void
    {
        $semester = Semester::getActiveSemester();
        $quarters = Quarter::where('semester_id', $semester->id)->get();

        $student_ids->each(function ($student_id) use ($semester, $quarters) {
            $studentSubjects = StudentSubjectGrade::where('student_id', $student_id)->distinct('batch_subject_id')->pluck('batch_subject_id');

            foreach ($studentSubjects as $subject_id) {
                $totalSubjectScore = 0.0;

                foreach ($quarters as $quarter) {
                    if ($quarter->end_date != null) { // quarter has ended
                        $totalSubjectScore += StudentSubjectGrade::where([
                            'student_id' => $student_id,
                            'batch_subject_id' => $subject_id,
                            'gradable_type' => 'App\Models\Quarter',
                            'gradable_id' => $quarter->id,
                        ])->sum('score');
                    } else { // quarter is ongoing
                        // Adjust the calculation as needed for ongoing quarters
                        $ongoingQuarterScore = StudentAssessmentsGrade::where([
                            'student_id' => $student_id,
                            'batch_subject_id' => $subject_id,
                            'gradable_type' => 'App\Models\Quarter',
                            'gradable_id' => $quarter->id,
                        ])->sum('score');

                        $totalSubjectScore += $ongoingQuarterScore;
                    }
                }

                // update semester grade for the subject
                StudentSubjectGrade::updateOrCreate(
                    [
                        'student_id' => $student_id,
                        'batch_subject_id' => $subject_id,
                        'gradable_type' => 'App\Models\Semester',
                        'gradable_id' => $semester->id,
                    ],
                    [
                        'score' => $totalSubjectScore / $quarters->count(),
                        'grade_scale_id' => GradeScale::get(
                            score: $totalSubjectScore,
                            maximum: 100  // adjust the maximum as per your grading system
                        )->id,
                    ]
                );
            }

            // update overall semester grade for the student
            $totalSemesterScore = StudentSubjectGrade::where([
                'student_id' => $student_id,
                'gradable_type' => 'App\Models\Semester',
                'gradable_id' => $semester->id,
            ])->sum('score');

            StudentGrade::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'gradable_type' => 'App\Models\Semester',
                    'gradable_id' => $semester->id,
                ],
                [
                    'score' => $totalSemesterScore / $studentSubjects->count(),
                    'grade_scale_id' => GradeScale::get(
                        score: $totalSemesterScore,
                        maximum: 100  // adjust the maximum as per your grading system
                    )->id,
                ]
            );
        });
    }

    /**
     * @return void
     *
     * Calculate and update students' batch subject rank
     * for a given assessment
     */
    private static function updateRank(Collection $student_ids): void
    {
        self::calculateSubjectRanks($student_ids, Quarter::class, Quarter::getActiveQuarter()->id);
        self::calculateSubjectRanks($student_ids, Semester::class, Semester::getActiveSemester()->id);

        self::calculateOverAllRanks($student_ids, Quarter::class, Quarter::getActiveQuarter()->id);
        self::calculateOverAllRanks($student_ids, Semester::class, Semester::getActiveSemester()->id);
    }

    /**
     * @return void
     *
     * Calculate and update students' batch subject rank for quarter or semester
     */
    private static function calculateSubjectRanks(Collection $student_ids, string $gradableType, int $gradableId): void
    {
        $students = DB::table('student_subject_grades')
            ->where('gradable_type', $gradableType)
            ->where('gradable_id', $gradableId)
            ->whereIn('student_id', $student_ids)
            ->orderBy('batch_subject_id')
            ->orderByDesc('score')
            ->get();

        $students->transform(function ($item, $key) use ($students, $gradableType) {
            static $lastBatchSubjectId = null;
            static $lastScore = null;
            static $rank = 0;

            if ($lastBatchSubjectId != $item->batch_subject_id || $lastScore != $item->score) {
                $lastBatchSubjectId = $item->batch_subject_id;
                $lastScore = $item->score;
                $rank = $students->where('batch_subject_id', $item->batch_subject_id)->where('score', '>', $item->score)->count() + 1;
            }

            DB::table('student_subject_grades')
                ->where('student_id', $item->student_id)
                ->where('batch_subject_id', $item->batch_subject_id)
                ->where('gradable_type', $gradableType)
                ->where('gradable_id', $item->gradable_id)
                ->update(['rank' => $rank]);

            return $item;
        });
    }

    /**
     * @return void
     *
     * Calculate and update students' batch rank for quarter or semester
     */
    private static function calculateOverAllRanks(Collection $student_ids, string $gradableType, int $gradableId): void
    {
        $students = DB::table('student_grades')
            ->where('gradable_type', $gradableType)
            ->where('gradable_id', $gradableId)
            ->whereIn('student_id', $student_ids)
            ->orderBy('gradable_id')
            ->orderByDesc('score')
            ->get();

        $students->transform(function ($item, $key) use ($students, $gradableType) {
            static $lastGradableId = null;
            static $lastScore = null;
            static $rank = 0;

            if ($lastGradableId != $item->gradable_id || $lastScore != $item->score) {
                $lastGradableId = $item->gradable_id;
                $lastScore = $item->score;
                $rank = $students->where('gradable_id', $item->gradable_id)->where('score', '>', $item->score)->count() + 1;
            }

            DB::table('student_grades')
                ->where('student_id', $item->student_id)
                ->where('gradable_id', $item->gradable_id)
                ->where('gradable_type', $gradableType)
                ->update(['rank' => $rank]);

            return $item;
        });
    }

    /**
     * @return Builder
     *
     * Query and return a student's assessments
     * for a given assessment type, class, and quarter
     */
    private static function fetchStudentAssessmentsByType(int $student_id, Assessment $assessment): Builder
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
