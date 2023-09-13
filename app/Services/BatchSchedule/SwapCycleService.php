<?php

namespace App\Services\BatchSchedule;

use App\Models\BatchSchedule;
use Illuminate\Support\Collection;

class SwapCycleService extends SwapService
{
    public static function handle(): void
    {
        parent::getUnAllocatedSchoolPeriods();

        self::startCycle();
    }

    protected static function startCycle(): void
    {
        foreach (parent::$unAllocatedBatchSubjects as $unAllocatedBatchSubject) {

            foreach (parent::getValidSlotsForUnAllocatedBatchSubject($unAllocatedBatchSubject) as $validSlot) {

                parent::$unAllocatedSchoolPeriods->filter(function ($unAllocatedSchoolPeriod) use ($unAllocatedBatchSubject) {
                    return $unAllocatedSchoolPeriod['batch_id'] === $unAllocatedBatchSubject['batchId'];
                })->each(function ($unAllocatedSchoolPeriod) use ($unAllocatedBatchSubject, $validSlot) {
                    self::getValidBatchSchedulesForSlot($unAllocatedSchoolPeriod, $unAllocatedBatchSubject['batchId'])->each(function ($validBatchSchedule) use ($unAllocatedSchoolPeriod, $validSlot, $unAllocatedBatchSubject) {
                        self::canBatchSubjectReallocated($validBatchSchedule, $unAllocatedSchoolPeriod, $validSlot, $unAllocatedBatchSubject);
                    });
                });

            }
        }
    }

    protected static function getValidBatchSchedulesForSlot($unAllocatedSchoolPeriod, $batchId): Collection
    {

        $batchSchedules = BatchSchedule::where('batch_id', $batchId)->whereNotNull('batch_subject_id')->with('batchSubject')->get();

        $validBatchSchedule = collect();

        $batchSchedules->each(function ($batchSchedule) use ($unAllocatedSchoolPeriod, $validBatchSchedule) {

            if (parent::canTeacherTeachInSlot($batchSchedule->batchSubject->teacher_id, $unAllocatedSchoolPeriod['dayName'], $unAllocatedSchoolPeriod['periodId']) && parent::canBatchSubjectBeAllocatedOnSlotDay($batchSchedule->batchSubject->id, $unAllocatedSchoolPeriod['dayName'], $batchSchedule->batchSubject->batch_id)) {
                $validBatchSchedule->push($batchSchedule);
            }
        });

        return $validBatchSchedule;
    }

    protected static function canBatchSubjectReallocated($validBatchSchedule, $unAllocatedSchoolPeriod, $validSlot, $unAllocatedBatchSubject): bool
    {

        $canBatchSubjectBeAllocated = parent::canBatchSubjectBeAllocatedOnSlotDay($validSlot['batchSubjectId'], $validBatchSchedule->day_of_week, $validBatchSchedule->batchSubject->batch_id);
        $canTeacherTeachInSlot = parent::canTeacherTeachInSlot($validSlot['teacherId'], $validBatchSchedule->day_of_week, $validBatchSchedule->school_period_id);

        if ($canTeacherTeachInSlot && $canBatchSubjectBeAllocated) {

            // Move G to P7
            parent::reAssignBatchSubjectToSlot(
                ['batchSubjectId' => $validBatchSchedule->batch_subject_id, 'batchId' => $validBatchSchedule->batch_id, 'teacherId' => $validBatchSchedule->batchSubject->teacher_id],
                ['dayName' => $validBatchSchedule->day_of_week, 'periodId' => $validBatchSchedule->school_period_id, 'batchId' => $validBatchSchedule->batch_id],
                ['dayName' => $unAllocatedSchoolPeriod['dayName'], 'periodId' => $unAllocatedSchoolPeriod['periodId'], 'batch_id' => $unAllocatedSchoolPeriod['batch_id']],
            );

            // Move B to G
            parent::reAssignBatchSubjectToSlot(
                $validSlot,
                $validSlot,
                ['dayName' => $validBatchSchedule->day_of_week, 'periodId' => $validBatchSchedule->school_period_id, 'batch_id' => $validBatchSchedule->batch_id],
            );

            // get the key of the $unAllocatedSchoolPeriod
            $key = parent::$unAllocatedSchoolPeriods->search(function ($unAllocatedSchoolPeriod) use ($unAllocatedBatchSubject) {
                return $unAllocatedSchoolPeriod['batch_id'] === $unAllocatedBatchSubject['batchId'];
            });
            // Move P7 to B
            parent::allocateBatchSubjectToSlot($unAllocatedBatchSubject, $validSlot, $key);

            return true;
        }

        return false;
    }
}
