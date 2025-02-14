<?php

namespace App\Services\BatchSchedule;

use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\BatchSubject;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatrixService
{
    protected int $activeSchoolYearId;

    protected static array $teacherAvailabilityMatrix;

    protected static array $batchSubjectAllocationMatrix;

    protected static array $scheduleMatrix;

    protected static $unAllocatedBatchSubjects;

    protected static $unAllocatedSchoolPeriods;

    private array $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

    public function __construct()
    {
        $this->activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id;
        self::$unAllocatedBatchSubjects = collect();
        self::$unAllocatedSchoolPeriods = collect();
    }

    public function createSchedule(): void
    {

        $batches = Batch::with(['subjects.subject', 'level.levelCategory'])
            ->where('school_year_id', $this->activeSchoolYearId)
            ->get()
            ->shuffle();

        $teachers = Teacher::whereHas('batchSubjects', function ($query) {
            $query->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            });
        })->with('batchSubjects.batch.level.levelCategory', 'batchSubjects.subject')
            ->get();

        $batchSchedules = BatchSchedule::whereIn('batch_id', $batches->pluck('id'))->with('batchSubject')->get();

        // Initialize matrices
        self::$teacherAvailabilityMatrix = $this->initializeTeacherAvailabilityMatrix($teachers, $batchSchedules);
        self::$batchSubjectAllocationMatrix = $this->initializeBatchSubjectAllocationMatrix($batches);
        self::$scheduleMatrix = $this->initializeScheduleMatrix($batches, $batchSchedules);

        // Loop through each batch to allocate subjects to available slots
        foreach ($batches as $batch) {
            $schoolPeriods = SchoolPeriod::where('school_year_id', $this->activeSchoolYearId)
                ->where('level_category_id', $batch->level->level_category_id)
                ->where('is_custom', false)
                ->get()
                ->shuffle();

            foreach ($batch->subjects->shuffle() as $batchSubject) {
                foreach ($this->daysOfWeek as $dayName) {
                    foreach ($schoolPeriods as $schoolPeriod) {
                        $isValid = $this->isValidSlot($batch->id, $batchSubject, $dayName, $schoolPeriod->id);

                        if ($isValid === 0) {
                            continue;
                        }

                        if ($isValid === 2) {
                            break 2;
                        }

                        // Allocate this slot to the current batch subject
                        self::$scheduleMatrix[$batch->id][$dayName][$schoolPeriod->id] = $batchSubject->id;

                        // Update teacher availability matrix to mark this teacher as unavailable in this slot
                        self::$teacherAvailabilityMatrix[$batchSubject->teacher_id][$dayName][$schoolPeriod->id] = false;

                        // Update batch subject allocation matrix to decrease the available frequency for this subject
                        self::$batchSubjectAllocationMatrix[$batch->id][$batchSubject->id]--;

                    }
                }
            }
        }

        $this->saveScheduleToDatabase();

        $this->isBatchScheduledFully();

        self::getUnAllocatedSchoolPeriods();

        // Swap unallocated school periods with allocated school periods
        $lastUnAllocatedSchoolPeriodsCount = self::$unAllocatedSchoolPeriods->count();

        $similarUnAllocatedSchoolPeriodsCount = 0;

        $index = 0;
        while (self::$unAllocatedBatchSubjects->count() > 0 && $index < 1500) {
            $index++;

            SwapService::handle();

            $this->isBatchScheduledFully(showLog: false);

            if (self::$unAllocatedSchoolPeriods->count() === $lastUnAllocatedSchoolPeriodsCount) {
                $similarUnAllocatedSchoolPeriodsCount++;
            } else {
                $similarUnAllocatedSchoolPeriodsCount = 0;
            }

            if ($similarUnAllocatedSchoolPeriodsCount > 50) {
                break;
            }

            $lastUnAllocatedSchoolPeriodsCount = self::$unAllocatedSchoolPeriods->count();
        }

        $this->isBatchScheduledFully();

        self::getUnAllocatedSchoolPeriods();

        $index = 0;
        while (self::$unAllocatedBatchSubjects->count() > 0 && $index === 0) {
            $index++;
            SwapCycleService::handle();
        }
    }

    protected function isValidSlot($batchId, $batchSubject, $dayName, $periodId): int
    {
        try {
            // Check if the subject is scheduled today
            if (in_array($batchSubject->id, self::$scheduleMatrix[$batchId][$dayName])) {
                return 0;
            }

            // Check if this time slot is not already occupied in the schedule
            if (self::$scheduleMatrix[$batchId][$dayName][$periodId] !== null) {
                return 0;
            }

            // Check if the teacher who teaches this subject is available at this time slot
            $teacherId = $batchSubject->teacher_id;
            if (! self::$teacherAvailabilityMatrix[$teacherId][$dayName][$periodId]) {
                return 0;
            }

            // Check if this subject has not exceeded its weekly frequency limit for this batch
            if (self::$batchSubjectAllocationMatrix[$batchId][$batchSubject->id] <= 0) {
                return 2;
            }

            // All checks passed, the slot is valid
            return 1;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            Log::info($exception->getTraceAsString());

            return false;
        }
    }

    protected function initializeTeacherAvailabilityMatrix($teachers, $batchSchedules): array
    {
        // Create a 3D matrix with teacher ids as rows, days of the week as the second dimension,
        // and school periods as the third dimension. Initialize all values to true indicating all teachers are available initially.
        $matrix = [];

        foreach ($teachers as $teacher) {
            // Get unique batch IDs
            $uniqueBatchIds = $teacher->batchSubjects->pluck('batch.id')->unique();

            // For each unique batch ID, get its level category ID and then find the corresponding school periods
            foreach ($uniqueBatchIds as $batchId) {
                $levelCategoryId = $teacher->batchSubjects->firstWhere('batch.id', $batchId)->batch->level->levelCategory->id;

                // Now, get the school periods for this level category ID
                $schoolPeriods = SchoolPeriod::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
                    ->where('level_category_id', $levelCategoryId)
                    ->where('is_custom', false)
                    ->get();

                foreach ($this->daysOfWeek as $day) {
                    foreach ($schoolPeriods as $schoolPeriod) {
                        $matrix[$teacher->id][$day][$schoolPeriod->id] = true;
                    }
                }
            }
        }

        if ($batchSchedules->count() > 0) {
            // Populate the matrix from the database, loop through every batch subject's schedule and set the matrix accordingly
            BatchSubject::with(['schedule', 'teacher'])->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })->get()->each(function ($batchSubject) use (&$matrix) {
                foreach ($batchSubject->schedule as $schedule) {
                    $matrix[$batchSubject->teacher_id][$schedule->day_of_week][$schedule->school_period_id] = false;
                }
            });
        }

        return $matrix;
    }

    protected function initializeBatchSubjectAllocationMatrix($batches): array
    {
        // Create a 2D matrix with batch ids as rows and subjects as columns
        // Initialize with the weekly frequency of each subject for each batch
        $matrix = [];

        foreach ($batches as $batch) {
            foreach ($batch->subjects as $batchSubject) {
                $matrix[$batch->id][$batchSubject->id] = $batchSubject->weekly_frequency;
            }
        }

        return $matrix;
    }

    protected function initializeScheduleMatrix($batches, $batchSchedules): array
    {
        // Create a 3D matrix with batch ids as one dimension, days of the week as the second dimension, and school periods as the third dimension
        // Initialize all values to null indicating no subjects are scheduled initially

        $matrix = [];

        if ($batchSchedules->count() > 0) {
            // Populate the matrix from the database
            foreach ($batchSchedules as $schedule) {
                $matrix[$schedule->batch_id][$schedule->day_of_week][$schedule->school_period_id] = $schedule->batch_subject_id;

                if ($schedule->batch_subject_id === null) {
                    continue;
                }

                // update the batch subject allocation matrix to decrease the available frequency for this subject
                self::$batchSubjectAllocationMatrix[$schedule->batch_id][$schedule->batch_subject_id]--;
            }
        } else {
            foreach ($batches as $batch) {

                // Get batch school periods
                $schoolPeriods = SchoolPeriod::where('school_year_id', $this->activeSchoolYearId)
                    ->where('level_category_id', $batch->level->level_category_id)
                    ->where('is_custom', false)
                    ->get();

                foreach ($this->daysOfWeek as $day) {
                    foreach ($schoolPeriods as $period) {
                        $matrix[$batch->id][$day][$period->id] = null;
                    }
                }
            }
        }

        return $matrix;
    }

    protected function saveScheduleToDatabase(): void
    {
        DB::beginTransaction();

        try {
            // Iterate over the schedule matrix and create database entries for each slot
            foreach (self::$scheduleMatrix as $batchId => $days) {
                foreach ($days as $dayName => $periods) {
                    foreach ($periods as $periodId => $batchSubjectId) {
                        // Check db if this slot already exists
                        $existingSchedule = BatchSchedule::where('batch_id', $batchId)
                            ->where('day_of_week', $dayName)
                            ->where('school_period_id', $periodId)
                            ->first();

                        if ($existingSchedule) {
                            // Skip if batch subject id is null
                            if ($batchSubjectId === null) {
                                continue;
                            }

                            // Check the subjects weekly limit
                            if (self::$batchSubjectAllocationMatrix[$batchId][$batchSubjectId] <= 0) {
                                continue;
                            }

                            // update the db and continue
                            $existingSchedule->update([
                                'batch_subject_id' => $batchSubjectId,
                            ]);

                            continue;
                        }

                        BatchSchedule::create([
                            'batch_id' => $batchId,
                            'day_of_week' => $dayName,
                            'school_period_id' => $periodId,
                            'batch_subject_id' => $batchSubjectId,
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            Log::error('Failed to save schedule to database', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            DB::rollBack();
        }
    }

    protected function isBatchScheduledFully($showLog = true): void
    {
        $batches = Batch::with(['subjects', 'level.levelCategory'])
            ->where('school_year_id', $this->activeSchoolYearId)
            ->get();

        foreach ($batches as $batch) {

            if ($showLog) {
                Log::info("{$batch->level->name}-{$batch->section}\n");
            }

            // Fetch the batch with its subjects
            $batch->load('subjects.subject', 'schedule');

            // Count the number of scheduled classes for each subject in this batch
            $scheduledSubjectCounts = [];
            foreach ($batch->schedule as $schedule) {
                if (! array_key_exists($schedule->batch_subject_id, $scheduledSubjectCounts)) {
                    $scheduledSubjectCounts[$schedule->batch_subject_id] = 0;
                }
                $scheduledSubjectCounts[$schedule->batch_subject_id]++;
            }

            // Check if the count matches the subject's weekly frequency
            foreach ($batch->subjects as $batchSubject) {
                $expectedCount = $batchSubject->weekly_frequency;
                $actualCount = $scheduledSubjectCounts[$batchSubject->id] ?? 0;

                $remainingUnAllocatedCount = $expectedCount - $actualCount;
                if ($remainingUnAllocatedCount > 0) {
                    if ($showLog) {
                        Log::info("Mismatch in batch: $batch->id for subject: {$batchSubject->subject->full_name} . { $batchSubject->id } . Expected: $expectedCount, Got: $actualCount");
                    }

                    // Loop through the remaining unallocated count and add them to the unallocated batch subjects array
                    for ($i = 0; $i < $remainingUnAllocatedCount; $i++) {
                        self::$unAllocatedBatchSubjects->push([
                            'batchId' => $batch->id,
                            'batchSubjectId' => $batchSubject->id,
                            'teacherId' => $batchSubject->teacher_id,
                        ]);
                    }
                }
            }
        }
    }

    protected static function getUnAllocatedSchoolPeriods(): void
    {
        foreach (self::$scheduleMatrix as $batchId => $days) {
            foreach ($days as $dayName => $periods) {
                foreach ($periods as $periodId => $batchSubjectId) {
                    if ($batchSubjectId === null) {
                        self::$unAllocatedSchoolPeriods->push([
                            'batch_id' => $batchId,
                            'dayName' => $dayName,
                            'periodId' => $periodId,
                        ]);
                    }
                }
            }
        }
    }
}
