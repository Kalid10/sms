<?php

namespace App\Jobs;

use App\Events\BatchScheduleEvent;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class GenerateBatchSchedulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $activeSchoolYearId;

    public function __construct()
    {
        $this->activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id;
    }

    public function handle()
    {
        // Adds 1GB of memory to the PHP process
        // ini_set('memory_limit', '1024M');

        $this->createScheduleWithoutPriority();
    }

    public function createScheduleWithoutPriority()
    {
        $batches = Batch::with(['subjects', 'level.levelCategory'])
            ->where('school_year_id', $this->activeSchoolYearId)
            ->get();

        $activeSchoolPeriods = (new SchoolPeriod)->getActivePeriods();

        if (count($activeSchoolPeriods) === 0) {
            return redirect()->back()->with('error', 'No active school periods found.');
        }

        foreach ($batches as $batch) {
            $schoolPeriods = SchoolPeriod::where('school_year_id', $this->activeSchoolYearId)
                ->where('level_category_id', $batch->level->level_category_id)
                ->get();

            $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

            $remainingSubjects = [];

            foreach ($daysOfWeek as $dayOfWeek) {
                $subjectsScheduledToday = [];

                // Initialize the remainingSubjects array with remaining slots
                if (empty($remainingSubjects)) {
                    foreach ($batch->subjects as $batchSubject) {
                        $remainingSubjects[] = [
                            'subject' => $batchSubject,
                            'remaining_slots' => $batchSubject->weekly_frequency,
                        ];
                    }
                }

                // Sort the remaining subjects by their remaining slots, descending order
                usort($remainingSubjects, function ($a, $b) {
                    return $b['remaining_slots'] - $a['remaining_slots'];
                });

                foreach ($schoolPeriods as $schoolPeriod) {
                    // Skip custom periods (breaks, lunches, etc.)
                    if ($schoolPeriod->is_custom) {
                        continue;
                    }

                    foreach ($remainingSubjects as $key => $remainingSubject) {
                        $batchSubject = $remainingSubject['subject'];

                        // Check if there are any subjects left to schedule
                        if ($batchSubject === null) {
                            break;
                        }

                        // Skip subjects with zero remaining slots
                        if ($remainingSubject['remaining_slots'] === 0) {
                            continue;
                        }

                        $teacherHasClass = BatchSchedule::whereHas('batchSubject', function ($query) use ($batchSubject) {
                            $query->where('teacher_id', $batchSubject->teacher_id);
                        })->where([
                            ['school_period_id', $schoolPeriod->id],
                            ['day_of_week', $dayOfWeek],
                        ])->exists();

                        // If the subject has not been scheduled today, schedule it
                        if (! $teacherHasClass && ! in_array($batchSubject->id, $subjectsScheduledToday)) {
                            $subjectsScheduledToday[] = $batchSubject->id;

                            BatchSchedule::create([
                                'batch_id' => $batch->id,
                                'school_period_id' => $schoolPeriod->id,
                                'batch_subject_id' => $batchSubject->id,
                                'day_of_week' => $dayOfWeek,
                            ]);

                            // Decrement the remaining slots for this subject
                            $remainingSubjects[$key]['remaining_slots']--;

                            break;
                        }
                    }
                }
            }
        }
        // Broadcast the event to the frontend
        Event::dispatch(new BatchScheduleEvent('success', 'Batch schedules generated successfully.'));
    }

    // This function tries to prioritize the subjects based on their priority but it's not working yet
    // TODO: Fix this function to prioritize subjects based on their priority
    private function prioritizeSchedule()
    {
        try {
            $batches = Batch::with(['subjects', 'level.levelCategory'])
                ->where('school_year_id', $this->activeSchoolYearId)
                ->get();

            foreach ($batches as $batch) {
                $schoolPeriods = SchoolPeriod::where('school_year_id', $this->activeSchoolYearId)
                    ->where('level_category_id', $batch->level->level_category_id)
                    ->get();

                $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

                foreach ($daysOfWeek as $dayOfWeek) {
                    // Retrieve schedules for this batch on the current day of the week
                    $schedules = BatchSchedule::with('batchSubject')
                        ->where('day_of_week', $dayOfWeek)
                        ->get();

                    // Sort schedules by the priority of their subjects
                    $sortedSchedules = $schedules->sortByDesc(function ($schedule) {
                        return $schedule->batchSubject->priority;
                    });

                    $availablePeriods = $schoolPeriods->filter(function ($schoolPeriod) {
                        return ! $schoolPeriod->is_custom;
                    });

                    foreach ($sortedSchedules as $schedule) {
                        $availablePeriod = $availablePeriods->first(function ($period) use ($schedule, $dayOfWeek) {
                            $teacherHasClass = BatchSchedule::whereHas('batchSubject', function ($query) use ($schedule) {
                                $query->where('teacher_id', $schedule->batchSubject->teacher_id);
                            })->where([
                                ['school_period_id', $period->id],
                                ['day_of_week', $dayOfWeek],
                            ])->exists();

                            $batchHasClass = BatchSchedule::where([
                                ['school_period_id', $period->id],
                                ['day_of_week', $dayOfWeek],
                            ])->exists();

                            return ! $teacherHasClass && ! $batchHasClass;
                        });

                        if ($availablePeriod) {
                            // Update the schedule with the available period
                            $schedule->update(['school_period_id' => $availablePeriod->id]);

                            // Remove the used period from the available periods collection
                            $availablePeriods = $availablePeriods->filter(function ($period) use ($availablePeriod) {
                                return $period->id !== $availablePeriod->id;
                            });
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    // This function is used to generate the batch schedules with priority but has a memory limit issue
    public function createSchedule()
    {
        // Log memory usage
        Log::info('Memory usage: '.memory_get_usage().' bytes');
        // Log memory limit
        Log::info('Memory limit: '.ini_get('memory_limit'));

        $activeSchoolYearId = SchoolYear::getActiveSchoolYear()->id;

        $chunkSize = 2; // Adjust the chunk size

        Log::info('Generating batch schedules...'.$chunkSize);
        Batch::with(['subjects', 'level.levelCategory'])
            ->where('school_year_id', $activeSchoolYearId)
            ->orderBy('id')
            ->cursor()
            ->each(function ($batch) use ($activeSchoolYearId) {
                $activeSchoolPeriods = (new SchoolPeriod)->getActivePeriods()->pluck('id');

                if ($activeSchoolPeriods->count() === 0) {
                    Log::error('No active school periods found.');

                    return;
                }

                $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

                $schoolPeriods = SchoolPeriod::where('school_year_id', $activeSchoolYearId)
                    ->where('level_category_id', $batch->level->level_category_id)
                    ->where('is_custom', false)
                    ->orderBy('id', 'desc') // Reverse order of periods
                    ->get();

                $remainingSubjects = collect($batch->subjects)->map(function ($batchSubject) {
                    return [
                        'subject' => $batchSubject,
                        'remaining_slots' => $batchSubject->weekly_frequency,
                    ];
                });

                // Log memory usage
                Log::info('Memory usage: '.memory_get_usage().' bytes');

                for ($dayIndex = 0; ! $remainingSubjects->isEmpty(); $dayIndex = ($dayIndex + 1) % count($daysOfWeek)) {
                    $dayOfWeek = $daysOfWeek[$dayIndex];

                    // Sort the remaining subjects by their priority and remaining slots, descending order
                    $remainingSubjects = $remainingSubjects->sortByDesc(function ($remainingSubject) {
                        return [$remainingSubject['subject']->priority, $remainingSubject['remaining_slots']];
                    });

                    $remainingSubjects = $remainingSubjects->map(function ($remainingSubject) use ($dayOfWeek, $schoolPeriods) {
                        if ($remainingSubject['remaining_slots'] > 0) {
                            $availablePeriod = $schoolPeriods->first(function ($schoolPeriod) use ($dayOfWeek, $remainingSubject) {
                                $periodOccupied = BatchSchedule::where([
                                    ['school_period_id', $schoolPeriod->id],
                                    ['day_of_week', $dayOfWeek],
                                ])->doesntExist();

                                $teacherHasClass = BatchSchedule::with('batchSubject')->where([
                                    ['batch_subject_id', $remainingSubject['subject']->id],
                                    ['school_period_id', $schoolPeriod->id],
                                    ['day_of_week', $dayOfWeek],
                                ])->doesntExist();

                                return $periodOccupied && $teacherHasClass;
                            });

                            if ($availablePeriod !== null) {
                                $batchSchedule = BatchSchedule::create([
                                    'batch_id' => $remainingSubject['subject']->batch_id,
                                    'school_period_id' => $availablePeriod->id,
                                    'batch_subject_id' => $remainingSubject['subject']->id,
                                    'day_of_week' => $dayOfWeek,
                                ]);
                                unset($batchSchedule);
                                // Decrement the remaining slots for this subject
                                $remainingSubject['remaining_slots']--;
                            }
                        }

                        return $remainingSubject;
                    });

                    // Remove subjects with zero remaining slots
                    $remainingSubjects = $remainingSubjects->filter(function ($remainingSubject) {
                        return $remainingSubject['remaining_slots'] > 0;
                    });
                }
            });

        Log::info('Batch schedules generated successfully.');
    }
}
