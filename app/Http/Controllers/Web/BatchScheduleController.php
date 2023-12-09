<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\BatchSchedule\SwapScheduleRequest;
use App\Imports\BatchScheduleImport;
use App\Jobs\GenerateBatchSchedulesJob;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\BatchScheduleConfig;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\SchoolPeriod;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Services\BatchSubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

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
                return BatchSubjectService::searchTeachers($request);
            }),
            'teacher' => Inertia::lazy(function () use ($request) {
                return $this->getTeacherSchedule($request);
            }),
            'isReadyForGeneratingSchedule' => $this->isReadyForGeneratingSchedule(),
        ]);
    }

    public function create(): RedirectResponse
    {
        GenerateBatchSchedulesJob::dispatch();

        return redirect()->back()->with('success', 'Batch schedules are being generated. Please check the logs for more information.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Start the import queue
        Excel::queueImport(new BatchScheduleImport(), $request->file('file'));

        return redirect()->back()->with('success', 'Batch schedules imported successfully.');
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

    private function getTeacherSchedule(Request $request)
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        return Teacher::find($request->input('teacher_id'))->load([
            'activeBatchSubjects' => function ($query) {
                $query->with(['batch.level']);
            },
        ]);
    }

    private function isReadyForGeneratingSchedule(): bool
    {
        return BatchSubject::whereIn('batch_id', Batch::active()->pluck('id')->toArray())->where('teacher_id', null)->count() === 0;
    }

    public function updateBatchSubjects(Request $request): void
    {
        BatchSubjectService::update($request);
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

        // Now let's check if the batch subjects have the same weekly frequency as the school periods
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
