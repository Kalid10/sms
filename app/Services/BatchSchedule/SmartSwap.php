<?php

namespace App\Services\BatchSchedule;

use App\Models\BatchSchedule;
use Illuminate\Support\Facades\Log;

class SmartSwap extends SwapService
{
    public static function smartSwap($teacherId, $dayOfWeek, $schoolPeriodId): bool
    {
        $batchSchedule = self::getTeacherSchedule($teacherId, $dayOfWeek, $schoolPeriodId);

        // Now let's try if we can swap this schedule with another schedule
        if ($batchSchedule !== null) {
            $batchSchedules = BatchSchedule::where('batch_id', $batchSchedule->batch_id)->whereNotNull('batch_subject_id')->with('batchSubject', 'schoolPeriod')->get();

            $response = false;
            $batchSchedules->each(function ($possibleSwappableSchedule) use ($dayOfWeek, $schoolPeriodId, $batchSchedule, &$response, $teacherId) {

                if (self::canTeacherTeachInSlot($possibleSwappableSchedule->batchSubject->teacher_id, $dayOfWeek, $schoolPeriodId) && self::canBatchSubjectBeAllocatedOnSlotDay($possibleSwappableSchedule->batchSubject->id, $dayOfWeek, $possibleSwappableSchedule->batchSubject->batch_id)) {
                    $possibleSwappableSchedule->batch_subject_id = $batchSchedule->batch_subject_id;
                    $possibleSwappableSchedule->save();

                    $batchSchedule->batch_subject_id = $possibleSwappableSchedule->batch_subject_id;
                    $batchSchedule->save();

                    // Update the teacher availability matrix
                    self::$teacherAvailabilityMatrix[$possibleSwappableSchedule->batchSubject->teacher_id][$dayOfWeek][$schoolPeriodId] = false;
                    self::$teacherAvailabilityMatrix[$possibleSwappableSchedule->batchSubject->teacher_id][$possibleSwappableSchedule->schoolPeriod->day_of_week][$possibleSwappableSchedule->school_period_id] = true;

                    self::$teacherAvailabilityMatrix[$teacherId][$dayOfWeek][$schoolPeriodId] = true;
                    self::$teacherAvailabilityMatrix[$teacherId][$possibleSwappableSchedule->schoolPeriod->day_of_week][$possibleSwappableSchedule->school_period_id] = false;

                    Log::info('SUCCESS: This is the new class and we have successfully swapped');

                    $response = true;

                    return false;

                }

                return true;
            });

            return $response;
        }

        return false;

    }

    private static function getTeacherSchedule($teacherId, $dayOfWeek, $schoolPeriodId)
    {
        $batchSchedule = BatchSchedule::where([
            ['day_of_week', $dayOfWeek],
            ['school_period_id', $schoolPeriodId],
        ])
            ->with('batchSubject')
            ->first();

        if ($batchSchedule === null || $batchSchedule->batch_subject_id === null) {
            return null;
        }

        return $batchSchedule->batchSubject->teacher_id === $teacherId ? $batchSchedule : null;
    }
}
