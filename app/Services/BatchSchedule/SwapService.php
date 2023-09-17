<?php

namespace App\Services\BatchSchedule;

use App\Models\BatchSchedule;
use App\Models\BatchSubject;
use Illuminate\Support\Collection;

//use Illuminate\Support\Facades\//Log;

class SwapService extends MatrixService
{
    public static function handle(): void
    {

        parent::$unAllocatedBatchSubjects->each(function ($unAllocatedBatchSubject) {

            //Log::info('================================================================');
            $allocationSuccess = self::allocateUnAllocatedBatchSubject($unAllocatedBatchSubject);
            //Log::info('================================================================');

            if ($allocationSuccess) {

                //Log::info('Count of $unAllocatedBatchSubjects: ' . count(parent::$unAllocatedBatchSubjects));

                //Log::info('Count of $unAllocatedSchoolPeriods: ' . count(parent::$unAllocatedSchoolPeriods));

            }

            //Log::info("\n");

        });

    }

    protected static function allocateUnAllocatedBatchSubject($unAllocatedBatchSubject): bool
    {

        //Log::info('allocateUnAllocatedBatchSubject $unAllocatedBatchSubject: ' . json_encode($unAllocatedBatchSubject));

        //        // Attempt to allocate to unAllocated period in batch
        //        if (self::attemptToAllocateToUnAllocatedPeriodInBatch($unAllocatedBatchSubject)) {
        //
        //            return true;
        //
        //        }
        return self::attemptToSwapWithAlreadyAllocatedPeriodInBatch($unAllocatedBatchSubject);

    }

    protected static function attemptToSwapWithAlreadyAllocatedPeriodInBatch($unAllocatedBatchSubject): bool
    {

        //Log::info('attemptToSwapWithAlreadyAllocatedPeriodInBatch $unAllocatedBatchSubject: ' . json_encode($unAllocatedBatchSubject));

        $validSlots = self::getValidSlotsForUnAllocatedBatchSubject($unAllocatedBatchSubject);

        foreach ($validSlots as $validSlotKey => $validSlot) {

            if (self::attemptToReAssignBatchSubjectInValidSlotToUnAllocatedSlot($unAllocatedBatchSubject, $validSlot, $validSlotKey)) {

                if (self::canTeacherTeachInSlot($unAllocatedBatchSubject['teacherId'], $validSlot['dayName'], $validSlot['periodId']) && self::canBatchSubjectBeAllocatedOnSlotDay($unAllocatedBatchSubject['batchSubjectId'], $validSlot['dayName'], $unAllocatedBatchSubject['batchId'])) {

                    //Log::info('Teacher can teach in slot');

                    //Log::info('Batch Subject can be allocated on slot day');

                    self::allocateBatchSubjectToSlot($unAllocatedBatchSubject, $validSlot, $validSlotKey);

                    //Log::info('Finished allocating $unAllocatedBatchSubject: ' . json_encode($unAllocatedBatchSubject) . ' to $validSlot: ' . json_encode($validSlot) . '...');

                    return true;

                }

            }

        }

        //Log::info('Failed to find $validSlot for $unAllocatedBatchSubject: ' . json_encode($unAllocatedBatchSubject) . '...');

        return false;

    }

    protected static function attemptToReAssignBatchSubjectInValidSlotToUnAllocatedSlot($unAllocatedBatchSubject, $validSlot, $validSlotKey): bool
    {

        //Log::info('attemptToReAssignBatchSubjectInValidSlotToUnAllocatedSlot $unAllocatedBatchSubject: ' . json_encode($unAllocatedBatchSubject) . ' $validSlot: ' . json_encode($validSlot));

        $batchSubjectToReAssign = self::getBatchSubjectFromSlot($validSlot, $unAllocatedBatchSubject['batchId']);

        // If there is a batch subject in the slot, we attempt to re-assign it to the unallocated slot
        if ($batchSubjectToReAssign) {

            //Log::info('filtering $unAllocatedSchoolPeriods by batchId: ' . $unAllocatedBatchSubject['batchId'] . '... ');

            $filteredUnAllocatedSchoolPeriods = self::$unAllocatedSchoolPeriods->filter(function ($unAllocatedSchoolPeriod) use ($unAllocatedBatchSubject) {

                return $unAllocatedSchoolPeriod['batch_id'] === $unAllocatedBatchSubject['batchId'];

            });

            //Log::info('$filteredUnAllocatedSchoolPeriods: ' . json_encode($filteredUnAllocatedSchoolPeriods));

            foreach ($filteredUnAllocatedSchoolPeriods as $unAllocatedSchoolPeriod) {

                //Log::info('attempting to re-assign $batchSubjectToReAssign: ' . json_encode($batchSubjectToReAssign) . ' to $unAllocatedSchoolPeriod: ' . json_encode($unAllocatedSchoolPeriod));

                if (self::canTeacherTeachInSlot($batchSubjectToReAssign['teacherId'], $unAllocatedSchoolPeriod['dayName'], $unAllocatedSchoolPeriod['periodId']) && self::canBatchSubjectBeAllocatedOnSlotDay($batchSubjectToReAssign['batchSubjectId'], $unAllocatedSchoolPeriod['dayName'], $batchSubjectToReAssign['batchId'])) {

                    //Log::info('Teacher can teach in slot');
                    //Log::info('Batch Subject can be allocated on slot day');

                    //Log::info('$unAllocatedSchoolPeriod: ' . json_encode(self::getBatchSubjectFromSlot($unAllocatedSchoolPeriod, $unAllocatedBatchSubject['batchId'])));
                    //Log::info('$unAllocatedSchoolPeriod: before update query' . json_encode($unAllocatedSchoolPeriod));
                    //Log::info("\n");
                    self::reAssignBatchSubjectToSlot($batchSubjectToReAssign, $validSlot, $unAllocatedSchoolPeriod);

                    //Log::info('Finished re-assigning $batchSubjectToReAssign: ' . json_encode($batchSubjectToReAssign) . ' to $unAllocatedSchoolPeriod: ' . json_encode($unAllocatedSchoolPeriod) . '...');

                    return true;

                }

            }

        }

        //Log::info('Failed to find $batchSubjectToReAssign for $unAllocatedBatchSubject: ' . json_encode($unAllocatedBatchSubject) . '...');

        return false;
    }

    protected static function getBatchSubjectFromSlot($validSlot, $batchId): ?array
    {

        //Log::info('getBatchSubjectFromSlot $validSlot: ' . json_encode($validSlot) . ' $batchId: ' . json_encode($batchId));

        $batchSubject = BatchSubject::find(parent::$scheduleMatrix[$batchId][$validSlot['dayName']][$validSlot['periodId']]);

        if (! $batchSubject) {
            return null;
        }

        //Log::info('returned $batchSubjectToReAssign: ' . json_encode([
        //                'batchSubjectId' => $batchSubject->id,
        //                'teacherId' => $batchSubject->teacher_id,
        //                'batchId' => $batchId,
        //                'weeklyFrequency' => $batchSubject->weekly_frequency,
        //            ])
        //        );

        return [
            'batchSubjectId' => $batchSubject->id,
            'teacherId' => $batchSubject->teacher_id,
            'batchId' => $batchId,
            'weeklyFrequency' => $batchSubject->weekly_frequency,
        ];
    }

    protected function getPossibleSlots($batchId, $batchSubjectId, $teacher): array
    {
        $possibleSlots = [];

        // Find possible slots from the teacher availability matrix
        foreach (self::$teacherAvailabilityMatrix as $teacherId => $days) {

            if ($teacher !== $teacherId) {
                continue;
            }

            foreach ($days as $dayName => $periods) {
                foreach ($periods as $periodId => $isAvailable) {
                    if ($isAvailable) {

                        // skip if batch subject is already scheduled in a slot in this day
                        if (in_array($batchSubjectId, self::$scheduleMatrix[$batchId][$dayName])) {
                            break;
                        }

                        $possibleSlots[] = [
                            'batch_id' => $batchId,
                            'batch_subject_id' => $batchSubjectId,
                            'day_name' => $dayName,
                            'period_id' => $periodId,
                        ];
                    }
                }
            }
        }

        return $possibleSlots;
    }

    protected static function getValidSlotsForUnAllocatedBatchSubject($unallocatedBatchSubject): ?Collection
    {

        //Log::info('getValidSlotsForUnAllocatedBatchSubject $unAllocatedBatchSubject: ' . json_encode($unallocatedBatchSubject));

        $validSlots = collect();

        foreach (self::$teacherAvailabilityMatrix as $teacherId => $days) {

            if ($unallocatedBatchSubject['teacherId'] !== $teacherId) {
                continue;
            }

            foreach ($days as $dayName => $periods) {

                foreach ($periods as $periodId => $isAvailable) {

                    if ($isAvailable) {

                        // skip if a batch subject is already scheduled in a slot in this day
                        if (in_array($unallocatedBatchSubject['batchSubjectId'], self::$scheduleMatrix[$unallocatedBatchSubject['batchId']][$dayName])) {
                            break;
                        }

                        $validSlots->push([
                            'batchId' => $unallocatedBatchSubject['batchId'],
                            'batchSubjectId' => $unallocatedBatchSubject['batchSubjectId'],
                            'dayName' => $dayName,
                            'periodId' => $periodId,
                            'teacherId' => $unallocatedBatchSubject['teacherId'],
                        ]);

                    }

                }

            }

        }

        //Log::info('returned $validSlots: ' . json_encode($validSlots));
        return $validSlots;
    }

    protected static function attemptToAllocateToUnAllocatedPeriodInBatch($unAllocatedBatchSubject): bool
    {

        // Filter unAllocatedSchoolPeriod by batchId
        $filteredUnAllocatedSchoolPeriods = array_filter(parent::$unAllocatedSchoolPeriods, (function ($unAllocatedSchoolPeriod) use ($unAllocatedBatchSubject) {

            return $unAllocatedSchoolPeriod['batch_id'] === $unAllocatedBatchSubject['batchId'];

        }));

        foreach ($filteredUnAllocatedSchoolPeriods as $key => $filteredUnAllocatedSchoolPeriod) {

            if (self::attemptToAllocateBatchSubjectToSlot($unAllocatedBatchSubject, $filteredUnAllocatedSchoolPeriod, $key)) {

                return true;

            }

        }

        return false;

    }

    protected static function attemptToAllocateBatchSubjectToSlot($batchSubject, $schoolPeriodSlot, $key): bool
    {

        if (self::canTeacherTeachInSlot($batchSubject['teacherId'], $schoolPeriodSlot['dayName'], $schoolPeriodSlot['periodId']) && self::canBatchSubjectBeAllocatedOnSlotDay($batchSubject['batchSubjectId'], $schoolPeriodSlot['dayName'], $batchSubject['batchId'])) {

            self::allocateBatchSubjectToSlot($batchSubject, $schoolPeriodSlot, $key);

            return true;
        }

        return false;
    }

    protected static function allocateBatchSubjectToSlot($batchSubject, $schoolPeriodSlot, $schoolPeriodSlotKey): void
    {

        // Update matrix
        parent::$scheduleMatrix[$batchSubject['batchId']][$schoolPeriodSlot['dayName']][$schoolPeriodSlot['periodId']] = $batchSubject['batchSubjectId'];

        //Log::info('$scheduleMatrix in batchId ' . $batchSubject['batchId'] . ' day ' . $schoolPeriodSlot['dayName'] . ' period ' . $schoolPeriodSlot['periodId'] . ' is now ' . parent::$scheduleMatrix[$batchSubject['batchId']][$schoolPeriodSlot['dayName']][$schoolPeriodSlot['periodId']]);

        parent::$unAllocatedBatchSubjects = parent::$unAllocatedBatchSubjects->filter(function ($unAllocatedBatchSubject) use ($batchSubject) {

            return $unAllocatedBatchSubject['batchSubjectId'] !== $batchSubject['batchSubjectId'];

        });

        //        unset(parent::$unAllocatedSchoolPeriods[$schoolPeriodSlotKey]);
        parent::$unAllocatedSchoolPeriods->filter(function ($unAllocatedSchoolPeriod) use ($schoolPeriodSlot) {
            return $unAllocatedSchoolPeriod['batch_id'] === $schoolPeriodSlot['batchId'] && $unAllocatedSchoolPeriod['dayName'] !== $schoolPeriodSlot['dayName'] && $unAllocatedSchoolPeriod['periodId'] !== $schoolPeriodSlot['periodId'];
        });

        parent::$teacherAvailabilityMatrix[$batchSubject['teacherId']][$schoolPeriodSlot['dayName']][$schoolPeriodSlot['periodId']] = false;

        //Log::info('Before Update: Ensuring there was NO $batchSubject with query ' . json_encode([
        //                'batch_id' => $batchSubject['batchId'],
        //                'batch_subject_id' => $batchSubject['batchSubjectId'],
        //                'school_period_id' => $schoolPeriodSlot['periodId'],
        //                'day_of_week' => $schoolPeriodSlot['dayName'],
        //            ]) . '... Count: ' . json_encode(BatchSchedule::where([
        //                'batch_id' => $batchSubject['batchId'],
        //                'batch_subject_id' => $batchSubject['batchSubjectId'],
        //                'school_period_id' => $schoolPeriodSlot['periodId'],
        //                'day_of_week' => $schoolPeriodSlot['dayName'],
        //            ])->count()));

        // Check if there is already a schedule for this batch subject in this slot, if there is, update it, else create a new one
        if (BatchSchedule::where([
            'batch_id' => $batchSubject['batchId'],
            'batch_subject_id' => $batchSubject['batchSubjectId'],
            'school_period_id' => $schoolPeriodSlot['periodId'],
            'day_of_week' => $schoolPeriodSlot['dayName'],
        ])->count() > 0) {

            //Log::info('Updating $batchSubject: ' . json_encode($batchSubject) . ' to $schoolPeriodSlot: ' . json_encode($schoolPeriodSlot));

            BatchSchedule::where([
                'batch_id' => $batchSubject['batchId'],
                'school_period_id' => $schoolPeriodSlot['periodId'],
                'day_of_week' => $schoolPeriodSlot['dayName'],
            ])->update([
                'batch_subject_id' => $batchSubject['batchSubjectId'],
            ])->save();

        } else {

            //Log::info('Creating $batchSubject: ' . json_encode($batchSubject) . ' to $schoolPeriodSlot: ' . json_encode($schoolPeriodSlot));

            BatchSchedule::create([
                'batch_id' => $batchSubject['batchId'],
                'batch_subject_id' => $batchSubject['batchSubjectId'],
                'school_period_id' => $schoolPeriodSlot['periodId'],
                'day_of_week' => $schoolPeriodSlot['dayName'],
            ]);

        }

        //Log::info('After Update: Ensuring there is only 1 $batchSubject with query ' . json_encode([
        //                'batch_id' => $batchSubject['batchId'],
        //                'batch_subject_id' => $batchSubject['batchSubjectId'],
        //                'school_period_id' => $schoolPeriodSlot['periodId'],
        //                'day_of_week' => $schoolPeriodSlot['dayName'],
        //            ]) . '... Count: ' . json_encode(BatchSchedule::where([
        //                'batch_id' => $batchSubject['batchId'],
        //                'batch_subject_id' => $batchSubject['batchSubjectId'],
        //                'school_period_id' => $schoolPeriodSlot['periodId'],
        //                'day_of_week' => $schoolPeriodSlot['dayName'],
        //            ])->count()));
    }

    protected static function reAssignBatchSubjectToSlot($batchSubject, $fromSchoolPeriodSlot, $toSchoolPeriodSlot): void
    {

        // Update schedule matrix, to reflect the new slot
        parent::$scheduleMatrix[$batchSubject['batchId']][$toSchoolPeriodSlot['dayName']][$toSchoolPeriodSlot['periodId']] = $batchSubject['batchSubjectId'];

        // Update the teacher availability matrix, make the old slot available for the teacher
        parent::$teacherAvailabilityMatrix[$batchSubject['teacherId']][$fromSchoolPeriodSlot['dayName']][$fromSchoolPeriodSlot['periodId']] = true;

        // Update the teacher availability matrix, make the new slot unavailable for the teacher
        parent::$teacherAvailabilityMatrix[$batchSubject['teacherId']][$toSchoolPeriodSlot['dayName']][$toSchoolPeriodSlot['periodId']] = false;

        // Remove from unallocated school periods the slot that was just allocated
        self::$unAllocatedSchoolPeriods->filter(function ($unAllocatedSchoolPeriod) use ($toSchoolPeriodSlot) {
            return $unAllocatedSchoolPeriod['batch_id'] === $toSchoolPeriodSlot['batch_id'] && $unAllocatedSchoolPeriod['dayName'] !== $toSchoolPeriodSlot['dayName'] && $unAllocatedSchoolPeriod['periodId'] !== $toSchoolPeriodSlot['periodId'];

        });

        //Log::info('toSchoolPeriodSlot: ' . json_encode($toSchoolPeriodSlot));
        //Log::info('$toSchoolPeriodSlot: before update query' . json_encode(self::getBatchSubjectFromSlot($toSchoolPeriodSlot, $batchSubject['batchId'])));

        // Add the old slot to the unallocated school periods
        self::$unAllocatedSchoolPeriods->push([
            'batch_id' => $fromSchoolPeriodSlot['batchId'],
            'dayName' => $fromSchoolPeriodSlot['dayName'],
            'periodId' => $fromSchoolPeriodSlot['periodId'],
        ]);

        // Check if there is already a schedule for the toSchoolPeriodSlot, if there is, update it, else create a new one
        if (BatchSchedule::where([
            'batch_id' => $batchSubject['batchId'],
            'school_period_id' => $toSchoolPeriodSlot['periodId'],
            'day_of_week' => $toSchoolPeriodSlot['dayName'],
        ])->count() > 0) {

            //Log::info('Updating $batchSubject and deleting: ' . json_encode($batchSubject) . ' to $toSchoolPeriodSlot: ' . json_encode($toSchoolPeriodSlot));

            BatchSchedule::where([
                'batch_id' => $batchSubject['batchId'],
                'school_period_id' => $toSchoolPeriodSlot['periodId'],
                'day_of_week' => $toSchoolPeriodSlot['dayName'],
            ])->first()->update([
                'batch_subject_id' => $batchSubject['batchSubjectId'],
            ]);

            // Delete the old schedule
            BatchSchedule::where([
                'batch_id' => $batchSubject['batchId'],
                'school_period_id' => $fromSchoolPeriodSlot['periodId'],
                'day_of_week' => $fromSchoolPeriodSlot['dayName'],
            ])->delete();

        } else {

            //Log::info('Updating $batchSubject: ' . json_encode($batchSubject) . ' to $toSchoolPeriodSlot: ' . json_encode($toSchoolPeriodSlot));

            $batchScheduleToBeUpdated = BatchSchedule::where([
                'batch_id' => $batchSubject['batchId'],
                'school_period_id' => $fromSchoolPeriodSlot['periodId'],
                'day_of_week' => $fromSchoolPeriodSlot['dayName'],
            ])->first();

            if ($batchScheduleToBeUpdated === null) {
                return;
            }

            //Log::info("This is the batch schedule to be updated: " . json_encode($batchScheduleToBeUpdated));
            // Update the batch schedule table
            $batchScheduleToBeUpdated->update([
                'school_period_id' => $toSchoolPeriodSlot['periodId'],
                'day_of_week' => $toSchoolPeriodSlot['dayName'],
            ]);

        }
    }

    protected static function canTeacherTeachInSlot($teacherId, $schoolPeriodSlotDay, $schoolPeriodSlotId): bool
    {
        //Log::info('canTeacherTeachInSlot $batchSubject: ' . json_encode($batchSubject) . ' $schoolPeriodSlot: ' . json_encode($schoolPeriodSlot) . '?');
        //Log::info(parent::$teacherAvailabilityMatrix[$batchSubject['teacherId']][$schoolPeriodSlot['dayName']][$schoolPeriodSlot['periodId']]);
        return parent::$teacherAvailabilityMatrix[$teacherId][$schoolPeriodSlotDay][$schoolPeriodSlotId];
    }

    protected static function canBatchSubjectBeAllocatedOnSlotDay($batchSubjectId, $dayName, $batchId): bool
    {
        //Log::info('canBatchSubjectBeAllocatedOnSlotDay $batchSubject: ' . json_encode($batchSubject) . ' $schoolPeriodSlot: ' . json_encode($schoolPeriodSlot) . '?');
        //Log::info(!in_array($batchSubject['batchSubjectId'], parent::$scheduleMatrix[$batchSubject['batchId']][$schoolPeriodSlot['dayName']]));
        return ! in_array($batchSubjectId, parent::$scheduleMatrix[$batchId][$dayName]);
    }
}
