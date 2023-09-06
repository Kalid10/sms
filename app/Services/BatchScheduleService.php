<?php

namespace App\Services;

use App\Events\BatchScheduleEvent;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\BatchSubject;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class BatchScheduleService
{
    private int $activeSchoolYearId;

    public function __construct()
    {
        $this->activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id;
    }

    public function createSchedule()
    {
        $this->clearOldSchedules();

        $batches = Batch::with(['subjects', 'level.levelCategory'])
            ->where('school_year_id', $this->activeSchoolYearId)
            ->get()
            ->shuffle();

        $allSchoolPeriods = SchoolPeriod::where('school_year_id', $this->activeSchoolYearId)->get();

        if (count($allSchoolPeriods) === 0) {
            return Event::dispatch(new BatchScheduleEvent('error', 'No active school periods found.'));
        }

        foreach ($batches as $batch) {
            Log::info('BATCH-LOOP:  batch_id '.$batch->id.' is being scheduled.');

            do {
                $schoolPeriods = SchoolPeriod::where('school_year_id', $this->activeSchoolYearId)
                    ->where('level_category_id', $batch->level->level_category_id)
                    ->get()
                    ->shuffle();

                $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

                foreach ($daysOfWeek as $dayOfWeek) {
                    foreach ($schoolPeriods as $schoolPeriod) {
                        Log::info('SCHOOL-PERIOD-LOOP: '.$dayOfWeek.'  school-period-id '.$schoolPeriod->id.' and is-custom '.$schoolPeriod->is_custom);

                        // Save custom period
                        if ($schoolPeriod->is_custom) {
                            BatchSchedule::create([
                                'batch_id' => $batch->id,
                                'batch_subject_id' => null,
                                'school_period_id' => $schoolPeriod->id,
                                'day_of_week' => $dayOfWeek,
                                'teacher_id' => null,
                            ]);

                            Log::info('SCHOOL-PERIOD-IS-CUSTOM-SUCCESS: '.$schoolPeriod->name.' and is-custom success');

                            continue;
                        }

                        // Get the unscheduled batch subjects
                        $unScheduledBatchSubjects = $this->getUnscheduledBatchSubjectsOfADay($batch->id, $dayOfWeek);

                        Log::info('UNSCHEDULED-BATCH-SUBJECTS-COUNT: '.count($unScheduledBatchSubjects));

                        foreach ($unScheduledBatchSubjects as $key => $batchSubject) {

                            Log::info('BATCH-SUBJECT-LOOP:  key '.$key.' and batch subject id '.$batchSubject->id.' and school period id '.$schoolPeriod->id.' and batch id '.$batch->id.' and day of week '.$dayOfWeek);
                            // Check if it is not more than the school period count
                            if ($key >= count($schoolPeriods)) {
                                break;
                            }

                            // Check if the batch subject is has reached its weekly frequency limit
                            if ($this->isBatchSubjectScheduledFully($batch->id, $batchSubject->id)) {
                                Log::info('BATCH-SUBJECT-SCHEDULED-FULLY-CHECKER: Batch subject '.$batchSubject->id.' is fully scheduled.');

                                continue;
                            }

                            // Check teacher availability
                            if ($this->isTeacherScheduled($batchSubject->teacher_id, $schoolPeriod->id, $dayOfWeek)) {
                                Log::info('IS-TEACHER-SCHEDULED-CHECKER:  Teacher '.$batchSubject->teacher_id.' is scheduled for school period '.$schoolPeriod->id.' and day of week '.$dayOfWeek);
                                // This is where swap and shit occurs
                                $isScheduledOrSwapped = $this->findSwappableTeacherSchedule($batchSubject->teacher_id, $schoolPeriod->id, $dayOfWeek, $schoolPeriods, $batch->id, $batchSubject);
                                if (! $isScheduledOrSwapped) {
                                    Log::info('The teacher has scheduled on the above subject and to make it worse it is not even not swappable');
                                }

                                // else find a solution
                            } else {
                                $isSuccess = $this->scheduleBatchSubject($schoolPeriod->id, $dayOfWeek, $batch->id, $batchSubject);
                                if (! $isSuccess) {
                                    Log::info('SCHOOL-PERIOD-NOT-AVAILABLE-CHECKER: Last minute school period checker returned false');

                                    continue;
                                }
                                break;
                            }
                        }

                    }
                }

                $unScheduledBatchSubjects = $this->getUnscheduledBatchSubjects($batch->id);

                if (count($unScheduledBatchSubjects) === 0) {
                    $isBatchFullyScheduled = true;
                }

                // If there are unscheduled batch subjects, then we will try to schedule them again
                $this->scheduleUnscheduledBatchSubjects($unScheduledBatchSubjects, $batch->id, $daysOfWeek, $schoolPeriods);

            } while (! $isBatchFullyScheduled);

            Log::info('BATCH-FULLY-SCHEDULED:  Batch '.$batch->id.' is fully scheduled.');
        }

        $this->isBatchScheduledFully($batches);
    }

    private function scheduleBatchSubject($schoolPeriodId, $dayOfWeek, $batchId, $batchSubject): bool
    {
        if ($this->isSchoolPeriodNotAvailable($schoolPeriodId, $dayOfWeek, $batchId)) {
            Log::info('SCHOOL-PERIOD-NOT-AVAILABLE-CHECKER: Last minute school period checker returned false');

            return false;
        }

        // Check if the weekly frequency is reached
        if ($this->isBatchSubjectScheduledFully($batchId, $batchSubject->id)) {
            Log::info('BATCH-SUBJECT-SCHEDULED-FULLY-CHECKER: Batch subject '.$batchSubject->id.' is fully scheduled.');

            return false;
        }

        // Check if teacher is available
        if ($this->isTeacherScheduled($batchSubject->teacher_id, $schoolPeriodId, $dayOfWeek)) {
            Log::info('TEACHER-HAS-SCHEDULE: #SORRY');

            return false;
        }

        BatchSchedule::create([
            'batch_id' => $batchId,
            'batch_subject_id' => $batchSubject->id,
            'school_period_id' => $schoolPeriodId,
            'day_of_week' => $dayOfWeek,
            'teacher_id' => $batchSubject->teacher_id,
        ]);

        Log::info('SUCCESS-BATCH-SUBJECT-SCHEDULED:  Batch subject '.$batchSubject->id.' - '.$dayOfWeek.' school-period-id: '.$schoolPeriodId.' batch-id: '.$batchId.' is scheduled.');

        return true;

    }

    private function isTeacherScheduled($teacherId, $schoolPeriodId, $dayOfWeek)
    {
        return BatchSchedule::whereHas('batchSubject', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
            ->where('school_period_id', $schoolPeriodId)
            ->where('day_of_week', $dayOfWeek)
            ->exists();
    }

    private function isBatchSubjectScheduledFully($batchId, $batchSubjectId): bool
    {
        $batchSubject = BatchSubject::find($batchSubjectId);

        $scheduledCount = BatchSchedule::where('batch_id', $batchId)
            ->where('batch_subject_id', $batchSubjectId)
            ->count();

        return $scheduledCount >= $batchSubject->weekly_frequency;
    }

    private function getUnscheduledBatchSubjects($batchId)
    {
        $batch = Batch::find($batchId)->load('subjects');

        return $batch->subjects->filter(function ($batchSubject) use ($batchId) {
            return ! $this->isBatchSubjectScheduledFully($batchId, $batchSubject->id);
        });
    }

    private function getUnscheduledBatchSubjectsOfADay($batchId, $dayOfWeek)
    {
        $batch = Batch::find($batchId)->load('subjects');

        $scheduledBatchSubjectIds = BatchSchedule::where('batch_id', $batchId)
            ->where('day_of_week', $dayOfWeek)
            ->whereNotNull('batch_subject_id')
            ->pluck('batch_subject_id');

        // Assuming the 'subjects' relation of the batch model contains subject IDs
        $allBatchSubjectIds = $batch->subjects->pluck('id');

        // Find unscheduled batch subject IDs
        $unscheduledBatchSubjectIds = $allBatchSubjectIds->diff($scheduledBatchSubjectIds);

        Log::info('UNSCHEDULED-BATCH-SUBJECT-IDS: '.$unscheduledBatchSubjectIds);
        Log::info('SCHEDULED-BATCH-SUBJECT-IDS: '.$scheduledBatchSubjectIds);

        // Fetch the actual unscheduled subjects based on their IDs
        return BatchSubject::whereIn('id', $unscheduledBatchSubjectIds)->get()->shuffle();
    }

    private function findSwappableTeacherSchedule($teacherId, $schoolPeriodId, $dayOfWeek, $schoolPeriods, $batchId, $batchSubject, $checkForFreePeriods = true): bool
    {
        Log::info('SWAP-TEACHER-SCHEDULE-CALLED');
        $isScheduledOrSwapped = false;

        if ($checkForFreePeriods) {
            Log::info('CHECK-FOR-FREE-PERIODS');
            // If there is a free period, return it
            foreach ($schoolPeriods as $schoolPeriod) {
                if ($schoolPeriod->id === $schoolPeriodId || $schoolPeriod->is_custom) {
                    continue;
                }

                // Check if there is a batch schedule for this school period, if not then we will return the school period
                $batchSchedule = BatchSchedule::where('school_period_id', $schoolPeriod->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->whereNull('batch_subject_id')
                    ->where('batch_id', $batchId)
                    ->first();

                if (! $batchSchedule) {
                    Log::info('FREE-PERIOD-FOUND: School period '.$schoolPeriod->id.' is free.');
                    Log::info($batchSchedule);
                    $isSuccess = $this->scheduleBatchSubject($schoolPeriod->id, $dayOfWeek, $batchId, $batchSubject);
                    if (! $isSuccess) {
                        Log::info('SCHOOL-PERIOD-NOT-AVAILABLE-CHECKER: Last minute school period checker returned false');

                        continue;
                    }
                    $isScheduledOrSwapped = true;
                    break;
                }
            }

            if ($isScheduledOrSwapped) {
                return true;
            }
        }

        // If we are here then there are no free periods, so we will try to swap with a teacher that will not be affected by the swap
        // Lets get all the batch schedules for that day
        $batchSchedules = BatchSchedule::where('day_of_week', $dayOfWeek)
            ->where('school_period_id', '!=', $schoolPeriodId)
            ->whereNotNull('batch_subject_id')
            ->where('batch_id', $batchId)
            ->with('batchSubject')
            ->get();

        // Lets get all the teachers that are scheduled for that day
        foreach ($batchSchedules as $batchSchedule) {
            Log::info('NO-FREE-PERIODS-FOUND--LOOPING-THROUGH-SCHEDULE-TRYING-TO-SWAP');
            // Get the teacher of this batch schedule
            $swappableTeacherId = $batchSchedule->batchSubject->teacher_id;

            // Check if the teacher is available for the school period
            if ($this->isTeacherScheduled($swappableTeacherId, $schoolPeriodId, $dayOfWeek)) {
                // TODO:: If there is no swappable teacher, leave it as is
                continue;
            }

            // If we are here then we have a swappable teacher, so lets swap
            $this->scheduleBatchSubject($schoolPeriodId, $dayOfWeek, $batchId, $batchSchedule->batchSubject);
            $this->scheduleBatchSubject($batchSchedule->school_period_id, $dayOfWeek, $batchId, $batchSubject);

            $isScheduledOrSwapped = true;
            Log::info('SWAPPED-TEACHER-SCHEDULE: Swapped teacher '.$swappableTeacherId.' with teacher '.$teacherId.' for school period '.$schoolPeriodId.' and day of week '.$dayOfWeek);
            break;

        }

        return $isScheduledOrSwapped;
    }

    private function scheduleUnscheduledBatchSubjects($unScheduledBatchSubjects, $batchId, $daysOfWeek, $schoolPeriods): bool
    {
        Log::info('SCHEDULE-UNSCHEDULED-BATCH-SUBJECTS');
        $freeBatchSchedules = [];
        // Lets get open slots of the batch from the week using schoolPeriods and daysOfWeek
        foreach ($daysOfWeek as $dayOfWeek) {
            foreach ($schoolPeriods as $schoolPeriod) {
                // Check if there is a batch schedule for this school period, if not then we will return the school period
                $batchSchedule = BatchSchedule::where('school_period_id', $schoolPeriod->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->first();

                if (! $batchSchedule) {
                    $freeBatchSchedules[] = [
                        'school_period_id' => $schoolPeriod->id,
                        'day_of_week' => $dayOfWeek,
                    ];
                }
            }
        }

        // TODO:: We can store what the unscheduled batch subjects tried to schedule, which means the school period and day of week
        foreach ($freeBatchSchedules as $freeBatchSchedule) {
            foreach ($unScheduledBatchSubjects as $unScheduledBatchSubject) {
                $isTeacherNotAvailableForThisPeriod = BatchSchedule::whereHas('batchSubject', function ($query) use ($unScheduledBatchSubject) {
                    $query->where('teacher_id', $unScheduledBatchSubject->teacher_id);
                })
                    ->where('school_period_id', $freeBatchSchedule['school_period_id'])
                    ->where('day_of_week', $freeBatchSchedule['day_of_week'])
                    ->first();

                if ($isTeacherNotAvailableForThisPeriod) {
                    continue;
                }

                $this->scheduleBatchSubject($freeBatchSchedule['school_period_id'], $freeBatchSchedule['day_of_week'], $batchId, $unScheduledBatchSubject);

                // Remove the unscheduled batch subject from the array
                $unScheduledBatchSubjects = array_filter($unScheduledBatchSubjects, function ($item) use ($unScheduledBatchSubject) {
                    return $item->id !== $unScheduledBatchSubject->id;
                });

                // Remove the free batch schedule from the array
                $freeBatchSchedules = array_filter($freeBatchSchedules, function ($item) use ($freeBatchSchedule) {
                    return $item['school_period_id'] !== $freeBatchSchedule['school_period_id'] && $item['day_of_week'] !== $freeBatchSchedule['day_of_week'];
                });

            }
        }

        if (count($unScheduledBatchSubjects) > 0) {
            // lets try to swap from monday to friday period 1 to last
            foreach ($daysOfWeek as $dayOfWeek) {
                foreach ($schoolPeriods as $schoolPeriod) {
                    foreach ($unScheduledBatchSubjects as $unScheduledBatchSubject) {
                        $isScheduledOrSwapped = $this->findSwappableTeacherSchedule($unScheduledBatchSubject->teacher_id, $schoolPeriod->id, $dayOfWeek, $schoolPeriods, $batchId, $unScheduledBatchSubject);
                        if ($isScheduledOrSwapped) {
                            // Log the unscheduled batch subject var type
                            Log::info('Unscheduled batch subject: '.gettype($unScheduledBatchSubject));
                            // Remove the unscheduled batch subject from the array
                            collect($unScheduledBatchSubjects)->filter(function ($item) use ($unScheduledBatchSubject) {
                                return $item->id !== $unScheduledBatchSubject->id;
                            });
                        }
                    }
                }
            }
        }

        if (count($unScheduledBatchSubjects) > 0) {
            // TODO:: Please god have mercy on us
            // TODO:: Notify the user that there are unscheduled batch subjects
            Log::info('There are unscheduled batch subjects for batch '.$batchId);
            Log::info('This is where you kill yourself');
            Log::info($unScheduledBatchSubjects);

            return false;
        } else {
            return true;
        }
    }

    private function isSchoolPeriodNotAvailable($schoolPeriodId, $dayOfWeek, $batchId)
    {

        $exists = BatchSchedule::where('school_period_id', $schoolPeriodId)
            ->where('day_of_week', $dayOfWeek)
            ->where('batch_id', $batchId)
            ->exists();

        if ($exists) {
            Log::info('SCHOOL-PERIOD-EXISTS: school-period-id: '.$schoolPeriodId.' '.$dayOfWeek.' batch-id: '.$batchId);
        }

        return $exists;
    }

    private function clearOldSchedules(): void
    {
        BatchSchedule::whereHas('batch', function ($query) {
            $query->where('school_year_id', $this->activeSchoolYearId);
        })->delete();
    }

    private function isBatchScheduledFully($batches)
    {
        foreach ($batches as $batch) {

            Log::info("\n");

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

                if ($actualCount != $expectedCount) {
                    Log::info("Mismatch in batch: $batch->id for subject: {$batchSubject->subject->full_name} . Expected: $expectedCount, Got: $actualCount");

                } else {
                    Log::info('BATCH-ID: '.$batch->id.' - '.$batchSubject->subject->full_name.' complete');
                    // Add new line

                }
            }
            Log::info("\n");
        }
    }

    public function teachersSchedule()
    {
        $teachers = Teacher::all();

        foreach ($teachers as $teacher) {
            $teacher->load([
                'batchSchedules' => function ($query) {
                    $query->orderBy('day_of_week');
                },
                'batchSchedules.batchSubject.subject',
                'batchSchedules.batch.level',
                'batchSchedules.schoolPeriod' => function ($query) {
                    $query->orderBy('start_time');
                },
            ]);

            Log::info('TEACHER-LOOP:  teacher_id '.$teacher->id);
            Log::info('Teacher weekly session count '.count($teacher->batchSchedules));
            foreach ($teacher->batchSchedules as $batchSchedule) {

                $dayOfWeek = $batchSchedule->day_of_week;
                $startTime = $batchSchedule->schoolPeriod->start_time;
                $subjectName = $batchSchedule->batchSubject->subject->full_name;
                $gradeLevel = $batchSchedule->batch->level->name;

                Log::info("{$gradeLevel}{$batchSchedule->batch->section}"."  {$dayOfWeek} - Period-{$batchSchedule->schoolPeriod->name}(id: {$batchSchedule->schoolPeriod->id}) - {$startTime}"." batchSubjectId: {$batchSchedule->batchSubject->id} -  {$subjectName}");
            }
        }

    }
}
