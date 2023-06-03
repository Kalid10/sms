<?php

namespace App\Helpers;

use App\Models\Batch;
use App\Models\BatchGrade;
use App\Models\BatchSubject;
use App\Models\BatchSubjectGrade;
use App\Models\GradeScale;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BatchStatisticsHelper
{
    public static function processBatchGrades($batchSubject): void
    {
        $batchSubject->load(['studentGrades', 'batch.level']);
        self::calculateGrades($batchSubject);
    }

    public static function calculateGrades($batchSubject): void
    {
        $quarter = Quarter::getActiveQuarter();
        $semester = Semester::getActiveSemester();
        $batchIds = Batch::where('level_id', $batchSubject->batch->level_id)->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->pluck('id');

        self::calculateBatchSubjectGrade($batchSubject, Quarter::class, $quarter->id);
        self::calculateBatchSubjectGrade($batchSubject, Semester::class, $semester->id);
        self::calculateBatchGrade($batchSubject, Quarter::class, $quarter->id);
        self::calculateBatchGrade($batchSubject, Semester::class, $semester->id);
        self::updateRank($batchIds);
    }

    private static function calculateBatchSubjectGrade($batchSubject, $gradableType, $gradableId): void
    {
        $studentGrades = $batchSubject->studentGrades()
            ->where('gradable_type', $gradableType)
            ->where('gradable_id', $gradableId)
            ->get();

        $averageGrade = $studentGrades->average('score');

        BatchSubjectGrade::updateOrCreate(
            [
                'batch_id' => $batchSubject->batch_id,
                'batch_subject_id' => $batchSubject->id,
                'gradable_type' => $gradableType,
                'gradable_id' => $gradableId,
            ],
            [
                'score' => $averageGrade,
                'grade_scale_id' => GradeScale::get(
                    score: $averageGrade,
                    maximum: 100
                )->id,
            ]
        );
    }

    private static function calculateBatchGrade($batchSubject, $gradableType, $gradableId): void
    {
        $batchSubjectIds = BatchSubject::where('batch_id', $batchSubject->batch_id)->pluck('id');

        $batchGrade = BatchSubjectGrade::whereIn('batch_subject_id', $batchSubjectIds)
            ->where('gradable_id', $gradableId)
            ->where('gradable_type', $gradableType)
            ->get();

        BatchGrade::updateOrCreate([
            'batch_id' => $batchSubject->batch_id,
            'gradable_type' => $gradableType,
            'gradable_id' => $gradableId,
        ],
            [
                'score' => $batchGrade->average('score'),
                'grade_scale_id' => GradeScale::get(
                    score: $batchGrade->average('score'),
                    maximum: 100
                )->id,
            ]);
    }

    private static function updateRank(Collection $batchIds): void
    {
        self::calculateSubjectRanks($batchIds, Quarter::class, Quarter::getActiveQuarter()->id);
        self::calculateSubjectRanks($batchIds, Semester::class, Semester::getActiveSemester()->id);

        self::calculateOverallRanks($batchIds, Quarter::class, Semester::getActiveSemester()->id);
        self::calculateOverallRanks($batchIds, Semester::class, Semester::getActiveSemester()->id);
    }

    /**
     * @return void
     *
     * Calculate and update batch's subject rank for quarter or semester
     */
    private static function calculateSubjectRanks(Collection $batchIds, string $gradableType, int $gradableId): void
    {
        $batches = DB::table('batch_subject_grades')
            ->select('batch_id', 'batch_subject_id', 'score')
            ->where('gradable_type', $gradableType)
            ->where('gradable_id', $gradableId)
            ->whereIn('batch_id', $batchIds)
            ->orderByDesc('score')
            ->get()
            ->map(function ($batch, $index) {
                $batch->rank = $index + 1;

                return $batch;
            });

        foreach ($batches as $batchRank) {
            DB::table('batch_subject_grades')
                ->where('batch_id', $batchRank->batch_id)
                ->update(['rank' => $batchRank->rank]);
        }
    }

    /**
     * @return void
     *
     * Calculate and update batches rank for quarter or semester
     */
    private static function calculateOverallRanks(Collection $batchIds, string $gradableType, int $gradableId): void
    {
        $batches = DB::table('batch_grades')
            ->select('batch_id', 'score')
            ->where('gradable_type', $gradableType)
            ->where('gradable_id', $gradableId)
            ->whereIn('batch_id', $batchIds)
            ->orderByDesc('score')
            ->get()
            ->map(function ($batch, $index) {
                $batch->rank = $index + 1;

                return $batch;
            });

        foreach ($batches as $batchRank) {
            DB::table('batch_grades')
                ->where('batch_id', $batchRank->batch_id)
                ->update(['rank' => $batchRank->rank]);
        }
    }
}
