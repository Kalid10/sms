<?php

namespace App\Http\Controllers\Web;

use App\Jobs\GenerateBatchSchedulesJob;
use App\Models\BatchSchedule;
use App\Models\BatchSubject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class BatchScheduleController extends Controller
{
    public function create(): RedirectResponse
    {
        GenerateBatchSchedulesJob::dispatch();

        return redirect()->back()->with('success', 'Batch schedules are being generated. Please check the logs for more information.');
    }

    public function checkSchedule()
    {
        // Retrieve batch subjects with related schedules and teachers
        $batchSubjects = BatchSubject::with(['schedule', 'teacher'])->get();

        $overScheduledSubjects = [];
        $teacherConflicts = [];

        // Check for over-scheduled subjects
        foreach ($batchSubjects as $batchSubject) {
            $scheduledCount = $batchSubject->schedule->count();

            if ($scheduledCount > $batchSubject->weekly_frequency) {
                $overScheduledSubjects[$batchSubject->id] = [
                    'weekly_frequency' => $batchSubject->weekly_frequency,
                    'scheduled_count' => $scheduledCount,
                ];
            }
        }

        // Retrieve all teachers with related schedules
        $teachers = Teacher::with('batchSchedules')->get();

        // Check for teacher conflicts
        foreach ($teachers as $teacher) {
            $teacherSchedules = $teacher->batchSchedules
                ->groupBy(function ($schedule) {
                    return $schedule->school_period_id.'_'.$schedule->day_of_week;
                })
                ->filter(function ($schedules) {
                    return $schedules->count() > 1;
                });

            if ($teacherSchedules->isNotEmpty()) {
                $teacherConflicts[$teacher->id] = $teacherSchedules->toArray();
            }
        }

        // Log over-scheduled subjects and teacher conflicts
        $this->logOverScheduledSubjects($overScheduledSubjects);
        $this->logTeacherConflicts($teacherConflicts);
        $this->checkScheduleData();
    }

    private function checkScheduleData()
    {
        // Get all batch schedules
        $batchSchedules = BatchSchedule::with(['batchSubject.subject', 'batchSubject.teacher.user', 'batchSubject.batch.level', 'schoolPeriod', 'batch.level'])->get();

        // Log teacher's schedules
        Log::info("Teacher's Schedules:");
        $teacherSchedules = $batchSchedules->filter(function ($schedule) {
            if ($schedule->batchSubject) {
                return $schedule->batchSubject->teacher_id == 1;
            }

            return false;
        });

        $teacherSchedules->each(function ($schedule) {
            Log::info($schedule->day_of_week.' -  Period: '.$schedule->schoolPeriod->name.' - Subject: '.$schedule->batchSubject->subject->full_name.' - '.$schedule->batchSubject->teacher->user->name);
        });

        // Log teacher subjects
        Log::info("Teacher's Subjects:");
        $teacher = Teacher::with('batchSubjects.subject')->find(25);
        $subjects = $teacher->batchSubjects->pluck('subject')->unique('id');

        $subjects->each(function ($subject) {
            Log::info($subject->full_name);
        });

        // Log subject's schedules
        Log::info("Subject's Schedules:");
        $subjectSchedules = $batchSchedules->filter(function ($schedule) {
            if ($schedule->batchSubject) {
                return $schedule->batchSubject->subject_id == 2;
            }

            return false;
        });

        $subjectSchedules->each(function ($schedule) {
            Log::info('Batch id: '.$schedule->batchSubject->batch->id.' - '.$schedule->day_of_week.' -  Period: '.$schedule->schoolPeriod->name.' Grade: '.$schedule->batchSubject->batch->level->name.'-'.$schedule->batchSubject->batch->section.' - Subject: '.$schedule->batchSubject->subject->full_name.' Priority: '.$schedule->batchSubject->subject->priority.' - '.$schedule->batchSubject->teacher->user->name);
        });

        // Log the schedules for batch 40
        Log::info("Now the schedules for batch 40 are: \n");
        $batchSchedules = $batchSchedules->filter(function ($schedule) {
            return $schedule->batch_id == 2;
        });

        $batchSchedules->each(function ($schedule) {
            if ($schedule->batchSubject) {
                Log::info('Batch id: '.$schedule->batch_id.' - '.$schedule->day_of_week.' -  Period: '.$schedule->schoolPeriod->name.' Grade: '.$schedule->batch->level->name.'-'.$schedule->batch->section.' - Subject: '.$schedule->batchSubject->subject->full_name.' Priority: '.$schedule->batchSubject->subject->priority.' - '.$schedule->batchSubject->teacher->user->name);
            }
        });
    }

    private function logOverScheduledSubjects(array $overScheduledSubjects)
    {
        if (count($overScheduledSubjects) > 0) {
            Log::info('The following batch subjects have been scheduled more often than their weekly frequency: total '.count($overScheduledSubjects)."\n");
            foreach ($overScheduledSubjects as $batchSubjectId => $data) {
                Log::info("Batch Subject ID: {$batchSubjectId} - Weekly Frequency: {$data['weekly_frequency']} - Scheduled Count: {$data['scheduled_count']}\n");
            }
        } else {
            Log::info('All batch subjects have schedules according to their weekly frequency.');
        }
    }

    private function logTeacherConflicts(array $teacherConflicts)
    {
        if (count($teacherConflicts) > 0) {
            Log::info("The following teachers have schedule conflicts:\n");
            foreach ($teacherConflicts as $teacherId => $conflicts) {
                Log::info("Teacher ID: {$teacherId}\n");
                foreach ($conflicts as $key => $schedules) {
                    Log::info("Conflict on school_period_id_day_of_week: {$key}\n");
                    foreach ($schedules as $schedule) {
                        Log::info("Batch ID: {$schedule->batch_id} - Batch Subject ID: {$schedule->batch_subject_id} - School Period ID: {$schedule->school_period_id} - Day of Week: {$schedule->day_of_week}\n");
                    }
                }
            }
        } else {
            Log::info('No teacher schedule conflicts found.');
        }
    }
}
