<?php

namespace App\Helpers;

use App\Models\Absentee;
use App\Models\Batch;
use App\Models\BatchGrade;
use App\Models\BatchSession;
use App\Models\BatchSubjectGrade;
use App\Models\Quarter;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;
use Carbon\Carbon;

class AbsenteeHelper
{
    public static function computeAbsenteeData($absentees, $batchSession): void
    {
        $batchSchedule = $batchSession->load('batchSchedule')->batchSchedule;
        $batchSubjectId = $batchSchedule->batch_subject_id;

        $batch = Batch::find($batchSchedule->batch_id);

        $completedBatchSessions = $batch->sessions()->where([
            ['teacher_id', '!=', null],
        ])->whereIn('status', [BatchSession::STATUS_COMPLETED, BatchSession::STATUS_IN_PROGRESS])
            ->get()->pluck('id');

        $allBatchSubjects = $batch->load('subjects')->subjects;

        foreach ($absentees as $absentee) {
            $student = Student::where('user_id', $absentee['user_id'])->first();

            self::updateStudentAttendanceStatistics($student, $completedBatchSessions, $batchSubjectId);
            self::updateBatchAttendanceStatistics($batch->load('students'), $batchSubjectId, $allBatchSubjects, $completedBatchSessions);
        }
    }

    private static function updateStudentAttendanceStatistics($student, $completedBatchSession, $batchSubjectId): void
    {
        $allStudentAbsenteeRecord = Absentee::whereIn('batch_session_id', $completedBatchSession)->where([
            ['user_id', $student->user_id],
        ])->get();

        $absenteePercentage = ($allStudentAbsenteeRecord->count() / $completedBatchSession->count()) * 100;

        $studentSubjectGrade = $student->studentSubjectGrades()->where([
            ['batch_subject_id', $batchSubjectId],
            ['student_id', $student->id],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->first();

        if ($studentSubjectGrade) {
            $studentSubjectGrade->update([
                'attendance' => 100 - $absenteePercentage,
            ]);
        } else {
            StudentSubjectGrade::create([
                'batch_subject_id' => $batchSubjectId,
                'student_id' => $student->id,
                'gradable_type' => Quarter::class,
                'gradable_id' => Quarter::getActiveQuarter()->id,
                'attendance' => 100 - $absenteePercentage,
            ]);
        }

        self::checkFlag($student, $absenteePercentage, $batchSubjectId);

        $studentSubjectGrades = $student->studentSubjectGrades()->where([
            ['student_id', $student->id],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->get();

        $studentSubjectGradesAverageAttendance = $studentSubjectGrades->avg('attendance');

        $studentGrade = StudentGrade::where([
            ['student_id', $student->id],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->first();

        if ($studentGrade) {
            $studentGrade->update([
                'attendance' => $studentSubjectGradesAverageAttendance,
            ]);
        } else {
            StudentGrade::create([
                'student_id' => $student->id,
                'gradable_type' => Quarter::class,
                'gradable_id' => Quarter::getActiveQuarter()->id,
                'attendance' => $studentSubjectGradesAverageAttendance,
            ]);
        }

        self::checkFlag($student, 100 - $studentSubjectGradesAverageAttendance);
    }

    private static function updateBatchAttendanceStatistics($batch, $batchSubjectId, $allBatchSubjects, $completedBatchSession): void
    {
        $studentsGrade = StudentSubjectGrade::whereIn('batch_subject_id', $allBatchSubjects->pluck('id'))
            ->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ]);

        // If there are any students that have not been graded yet, then we need to add them to the average with value 100
        $diffStudentIds = $batch->students->pluck('student_id')->diff($studentsGrade->pluck('student_id'));
        $averageStudentAttendance = (($diffStudentIds->count() * 100) + $studentsGrade->sum('attendance')) / $batch->students->count();

        $batchSubjectGrade = BatchSubjectGrade::where([
            ['batch_id', $batch->id],
            ['batch_subject_id', $batchSubjectId],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->first();

        if ($batchSubjectGrade) {
            $batchSubjectGrade->update([
                'attendance' => $averageStudentAttendance,
            ]);
        } else {
            BatchSubjectGrade::create([
                'batch_id' => $batch->id,
                'batch_subject_id' => $batchSubjectId,
                'gradable_type' => Quarter::class,
                'gradable_id' => Quarter::getActiveQuarter()->id,
                'attendance' => $averageStudentAttendance,
            ]);
        }

        $batchSubjectGrades = BatchSubjectGrade::whereIn('batch_subject_id', $allBatchSubjects->pluck('id'))
            ->where([
                ['batch_id', $batch->id],
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ]);

        // If there are any subjects that have not been graded yet, then we need to add them to the average with value 100
        $diffSubjectIds = $allBatchSubjects->pluck('id')->diff($batchSubjectGrades->pluck('batch_subject_id'));
        $averageBatchAttendance = (($diffSubjectIds->count() * 100) + $batchSubjectGrades->sum('attendance')) / $allBatchSubjects->count();

        $batchGrade = BatchGrade::where([
            ['batch_id', $batch->id],
            ['gradable_type', Quarter::class],
            ['gradable_id', Quarter::getActiveQuarter()->id],
        ])->first();

        if ($batchGrade) {
            $batchGrade->update([
                'attendance' => $averageBatchAttendance,
            ]);
        } else {
            BatchGrade::create([
                'batch_id' => $batch->id,
                'gradable_type' => Quarter::class,
                'gradable_id' => Quarter::getActiveQuarter()->id,
                'attendance' => $averageBatchAttendance,
            ]);
        }
    }

    private static function checkFlag($student, $absenteeValue, $batchSubjectId = null): void
    {
        if ($absenteeValue >= 25) {
            StudentHelper::flagStudent(
                $student->id,
                'attendance',
                'Student has been marked as absentee, with attendance percentage of '. 100 - $absenteeValue.'%.',
                null,
                $batchSubjectId,
                Carbon::now()->addWeek()
            );
        }
    }
}
