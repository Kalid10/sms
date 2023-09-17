<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\BatchSchedule\SwapScheduleRequest;
use App\Jobs\GenerateBatchSchedulesJob;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\BatchScheduleConfig;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BatchScheduleController extends Controller
{
    public function index(Request $request): Response
    {
        $request->validate([
            'batch_id' => 'exists:batches,id',
        ]);

        if ($request->has('batch_id')) {
            $selectedBatch = Batch::find($request->input('batch_id'))
                ->load(
                    'level', 'schoolYear',
                    'schedule.batchSubject.teacher.user',
                    'schedule.batchSubject.subject',
                    'schedule.schoolPeriod'
                );
        } else {
            $selectedBatch = null;
        }

        // TODO:For the time being I am checking if there are any batch schedules in the database since it the first year,
        // TODO:but if there are schedules from previous school years, this will not work.
        $batchSchedules = BatchSchedule::all();

        $batchScheduleConfig = BatchScheduleConfig::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->first();

        return Inertia::render('Admin/BatchSchedules/Index', [
            'levels' => Level::whereHas('batches', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })->with(['batches' => function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            }])->get(),
            'selectedBatch' => $selectedBatch,
            'schoolPeriods' => function () use ($selectedBatch) {
                if ($selectedBatch) {
                    SchoolPeriod::where([
                        'level_category_id' => $selectedBatch->level->load('levelCategory')->levelCategory->id,
                        'school_year_id' => $selectedBatch->schoolYear->id,
                    ]);
                }

                return null;
            },
            'isBatchScheduleGenerated' => $batchSchedules->count() > 0,
            'batchScheduleConfig' => $batchScheduleConfig,
            'batchSubjects' => $selectedBatch ? $this->getBatchSubjects($request->input('batch_id')) : null,
            'teachers' => Inertia::lazy(function () use ($request) {
                return $this->searchTeachers($request);
            }),
        ]);
    }

    public function create(): RedirectResponse
    {
        GenerateBatchSchedulesJob::dispatch();

        return redirect()->back()->with('success', 'Batch schedules are being generated. Please check the logs for more information.');
    }

    public function checkSchedule(): void
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

    public function swapSchedules(SwapScheduleRequest $request): RedirectResponse
    {
        $scheduleA = BatchSchedule::find($request->input('schedule_a'))->load('batchSubject');
        $scheduleB = BatchSchedule::find($request->input('schedule_b'))->load('batchSubject');
        $scheduleBBatchSubjectId = $scheduleB->batchSubject->id;
        $scheduleABatchSubjectId = $scheduleA->batchSubject->id;

        // Swap batch subjects
        $scheduleA->batch_subject_id = $scheduleBBatchSubjectId;
        $scheduleB->batch_subject_id = $scheduleABatchSubjectId;

        $scheduleA->save();
        $scheduleB->save();

        return redirect()->back()->with('success', 'Schedules swapped successfully.');
    }

    private function checkScheduleData(): void
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

    private function logOverScheduledSubjects(array $overScheduledSubjects): void
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

    private function logTeacherConflicts(array $teacherConflicts): void
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

    public function saveConfiguration(Request $request): RedirectResponse
    {
        $request->validate([
            'daily_period_limit' => 'required|integer',
            'weekly_period_limit' => 'required|integer',
        ]);

        // Check if there is a configuration for the current school year if so update else creates
        $batchScheduleConfig = BatchScheduleConfig::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->first();

        if ($batchScheduleConfig) {
            $batchScheduleConfig->update([
                'max_periods_per_day' => $request->input('daily_period_limit'),
                'max_periods_per_week' => $request->input('weekly_period_limit'),
            ]);

            return redirect()->back()->with('success', 'School year configurations updated successfully.');
        }
        BatchScheduleConfig::create([
            'max_periods_per_day' => $request->input('daily_period_limit'),
            'max_periods_per_week' => $request->input('weekly_period_limit'),
            'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
        ]);

        return redirect()->back()->with('success', 'School year configurations created successfully.');
    }

    private function getBatchSubjects($batchId): Collection
    {

        if (! $batchId) {
            return collect();
        }

        $batch = Batch::find($batchId);

        return $batch->load('subjects.subject', 'subjects.teacher.user')->subjects->sortBy('priority');
    }

    private function searchTeachers(Request $request)
    {
        $request->validate([
            'search_teacher_text' => 'nullable|string',
        ]);

        return Teacher::whereHas('user', function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->input('search_teacher_text')}%");
        })->with(['user', 'activeBatchSubjects'])->get()->take(7);
    }

    public function updateBatchSubjects(Request $request): RedirectResponse
    {
        $request->validate([
            'batch_subjects' => 'required|array',
            'batch_subjects.*.id' => 'required|exists:batch_subjects,id',
            'batch_subjects.*.teacher_id' => 'required|exists:teachers,id',
            'batch_subjects.*.weekly_frequency' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->input('batch_subjects') as $batchSubject) {

                // Get the teachers batch subjects for this school year and check if the teacher total weekly frequency is not exceeded from the batch_schedule_config table
                $teacherBatchSubjects = BatchSubject::where('teacher_id', $batchSubject['teacher_id'])->with(['batch' => function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                }])->get();

                $teacherTotalWeeklyFrequency = $teacherBatchSubjects->sum('weekly_frequency');

                $batchScheduleConfig = BatchScheduleConfig::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->first();

                if ($teacherTotalWeeklyFrequency + $batchSubject['weekly_frequency'] > $batchScheduleConfig->max_periods_per_week) {
                    DB::rollBack();

                    return redirect()->back()->withErrors(['error', $batchSubject['teacher']['user']['name'].' has exceeded the weekly frequency limit of '.$batchScheduleConfig->max_periods_per_week.' periods per week.']);
                }

                $batchSubjectModel = BatchSubject::find($batchSubject['id']);
                $batchSubjectModel->weekly_frequency = $batchSubject['weekly_frequency'];
                $batchSubjectModel->teacher_id = $batchSubject['teacher_id'];
                $batchSubjectModel->save();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Batch subjects updated successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while updating batch subjects.');
        }

    }

    public function generateBatchSchedules(): RedirectResponse
    {
        // Get active school year batches
        $batches = Batch::with(['subjects', 'level.levelCategory'])
            ->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->get();

        // Check if there is any batch subject which is not custom but there is no teacher assigned to it but should be from the current school year
        $isBatchSubjectComplete = $batches->map(function ($batch) {
            $batchIsNotComplete = $batch->subjects->filter(function ($subject) {
                return $subject->teacher_id == null && $subject->is_custom == false;
            });

            if ($batchIsNotComplete->count() > 0) {
                return $batch;
            }

            return null;
        });

        // remove the nulls from the batch subjects
        $isBatchSubjectComplete = $isBatchSubjectComplete->filter(function ($batchSubject) {
            return $batchSubject != null;
        });

        $isReady = false;

        if (count($isBatchSubjectComplete) === 0) {
            $isReady = true;
        }

        // get the batch section and level names of the batch subjects
        $batchNames = $isBatchSubjectComplete->map(function ($batch) {
            return "{$batch->level->name}{$batch->section}";
        })->implode(', ');

        if (! $isReady) {
            $errorMessage =
                'Missing teachers! Assign teachers to the following classes '.
                'before generating batch schedules: '.$batchNames;

            return redirect()->back()->withErrors([
                'schedule_generator_error' => $errorMessage,
            ]);
        }

        // Now lets check if the batch subjects have the same weekly frequency as the school periods
        $batches = $batches->map(function ($batch) {
            $schoolPeriods = SchoolPeriod::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
                ->where('level_category_id', $batch->level->levelCategory->id)
                ->where('is_custom', false)
                ->get();

            // Get me the sum of the weekly frequency of the batch subjects
            $batchSubjectsWeeklyFrequency = $batch->subjects->sum('weekly_frequency');

            if ($schoolPeriods->count() * 5 != $batchSubjectsWeeklyFrequency) {
                return $batch;
            }

            return null;
        });

        // remove the nulls from the batch subjects
        $batches = $batches->filter(function ($batchSubject) {
            return $batchSubject != null;
        });

        if ($batches->count() > 0) {
            $batchNames = $batches->map(function ($batch) {
                return "{$batch->level->name}{$batch->section}";
            })->implode(', ');

            $errorMessage =
                'Batches with different weekly frequency than the school periods: '.$batchNames;

            return redirect()->back()->withErrors([
                'schedule_generator_error' => $errorMessage,
            ]);
        }

        $schoolPeriods = SchoolPeriod::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->where('level_category_id', 1)->get();

        GenerateBatchSchedulesJob::dispatch();

        return redirect()->back()->with('success', 'Batch schedules are being generated. Please check the logs for more information.');
    }
}
