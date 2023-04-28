<?php

namespace App\Jobs;

use App\Events\BatchScheduleEvent;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

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
//        ini_set('memory_limit', '1024M');

        $this->createSchedule();
    }

    public function createSchedule(): array|RedirectResponse|null
    {
        $batches = Batch::with(['subjects.subject', 'level.levelCategory'])
            ->where('school_year_id', $this->activeSchoolYearId)
            ->get();

        $activeSchoolPeriods = (new SchoolPeriod)->activePeriods();

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
                            'priority' => $batchSubject->subject->priority,
                        ];
                    }
                }

                // Sort remaining subjects based on priority (lower value indicates higher priority)
                usort($remainingSubjects, function ($a, $b) {
                    return $a['priority'] <=> $b['priority'];
                });

                // Initialize the scheduled count for each batch subject
                $scheduledCountByBatchSubject = [];
                foreach ($remainingSubjects as $remainingSubject) {
                    $scheduledCountByBatchSubject[$remainingSubject['subject']->id] = 0;
                }

                foreach ($schoolPeriods as $schoolPeriod) {
                    // Schedule custom periods
                    if ($schoolPeriod->is_custom) {
                        BatchSchedule::create([
                            'batch_id' => $batch->id,
                            'school_period_id' => $schoolPeriod->id,
                            'batch_subject_id' => null,
                            'day_of_week' => $dayOfWeek,
                        ]);

                        continue;
                    }

                    // Sort remaining subjects by the number of remaining slots, prioritizing subjects with more remaining slots
                    usort($remainingSubjects, function ($a, $b) {
                        return $b['remaining_slots'] <=> $a['remaining_slots'];
                    });

                    // Find a suitable subject to schedule in this period
                    $foundSuitableSubject = false;
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

                        // Check if the subject has been scheduled enough times for the current week
                        if ($scheduledCountByBatchSubject[$batchSubject->id] >= $batchSubject->weekly_frequency) {
                            continue;
                        }

                        // Check if the subject has already been scheduled today
                        if (in_array($batchSubject->id, $subjectsScheduledToday)) {
                            continue;
                        }

                        $teacherHasClass = false;
                        $teacherSchedules = BatchSchedule::whereHas('batchSubject', function ($query) use ($batchSubject) {
                            $query->where('teacher_id', $batchSubject->teacher_id);
                        })
                            ->where('day_of_week', $dayOfWeek)
                            ->with('schoolPeriod')
                            ->get();

                        $currentPeriodStartTime = Carbon::createFromFormat('H:i:s', $schoolPeriod->start_time);
                        $currentPeriodEndTime = $currentPeriodStartTime->copy()->addMinutes($schoolPeriod->duration);

                        foreach ($teacherSchedules as $teacherSchedule) {
                            $teacherPeriodStartTime = Carbon::createFromFormat('H:i:s', $teacherSchedule->schoolPeriod->start_time);
                            $teacherPeriodEndTime = $teacherPeriodStartTime->copy()->addMinutes($teacherSchedule->schoolPeriod->duration);

                            if ($currentPeriodStartTime->between($teacherPeriodStartTime, $teacherPeriodEndTime) || $currentPeriodEndTime->between($teacherPeriodStartTime, $teacherPeriodEndTime)) {
                                $teacherHasClass = true;
                                break;
                            }
                        }

                        $batchHasClass = BatchSchedule::where([
                            ['batch_id', $batch->id],
                            ['school_period_id', $schoolPeriod->id],
                            ['day_of_week', $dayOfWeek],
                        ])->exists();

                        // If the subject has not been scheduled today and there is no overlap for both teacher and batch, schedule it
                        if (! $teacherHasClass && ! $batchHasClass) {
                            $subjectsScheduledToday[] = $batchSubject->id;

                            BatchSchedule::create([
                                'batch_id' => $batch->id,
                                'school_period_id' => $schoolPeriod->id,
                                'batch_subject_id' => $batchSubject->id,
                                'day_of_week' => $dayOfWeek,
                            ]);

                            // Decrement the remaining slots for this subject
                            $remainingSubjects[$key]['remaining_slots']--;

                            // Increment the scheduled count for this subject
                            $scheduledCountByBatchSubject[$batchSubject->id]++;

                            break;
                        }
                    }
                }
            }
        }
        // Broadcast the event to the frontend
        return Event::dispatch(new BatchScheduleEvent('success', 'Batch schedules generated successfully.'));
    }
}