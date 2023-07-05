<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Batches\CreateBulkRequest;
use App\Http\Requests\Batches\CreateRequest;
use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Flag;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentNote;
use App\Services\StudentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BatchController extends Controller
{
    public function create(CreateRequest $request): Response|RedirectResponse
    {
        $validated = $request->validated();

        // Get active school year
        $schoolYear = SchoolYear::getActiveSchoolYear();

        if (! $schoolYear) {
            return redirect()->back()->withErrors(['school_year' => 'Active school year not found!']);
        }

        // Check if min_students and max_students are null, if so get default from .env
        if (! $validated['min_students']) {
            $validated['min_students'] = env('DEFAULT_MIN_STUDENTS', 10);
        }

        if (! $validated['max_students']) {
            $validated['max_students'] = env('DEFAULT_MAX_STUDENTS', 50);
        }
        Batch::create(array_merge(
            $validated, ['school_year_id' => $schoolYear->id]
        ));

        return redirect()->back()->with('success', 'Batch created successfully!');
    }

    public function createBulk(CreateBulkRequest $request): RedirectResponse|Response
    {
        $validated = $request->validated();

        $schoolYearId = SchoolYear::getActiveSchoolYear()->id;

        if (! $schoolYearId) {
            return redirect()->back()->withErrors(['school_year' => 'Active school year not found!']);
        }

        DB::beginTransaction();
        try {
            foreach ($validated['batches'] as $batch) {
                $levelId = $batch['level_id'];

                if (is_array($batch['level_id'])) {
                    $levelId = Level::create([
                        'name' => $batch['level_id']['name'],
                    ])->id;
                }

                if (! isset($batch['min_students'])) {
                    $batch['min_students'] = env('DEFAULT_MIN_STUDENTS', 10);
                }

                if (! isset($batch['max_students'])) {
                    $batch['max_students'] = env('DEFAULT_MAX_STUDENTS', 50);
                }

                if ($batch['min_students'] > $batch['max_students']) {
                    return redirect()->back()->withErrors(['batches' => 'Minimum students cannot be greater than maximum students!']);
                }

                $noOfSections = $batch['no_of_sections'];

                if (Batch::where('level_id', $levelId)
                        ->where('school_year_id', $schoolYearId)->count() === $noOfSections) {
                    continue;
                }

                for ($i = 0; $i < $noOfSections; $i++) {
                    $section = chr(65 + $i);
                    Batch::create([
                        'level_id' => $levelId,
                        'school_year_id' => $schoolYearId,
                        'section' => $section,
                        'min_students' => $batch['min_students'],
                        'max_students' => $batch['max_students'],
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return redirect()->back()->withErrors(['batches' => 'Something went wrong!']);
        }

        return redirect()->back()->with('success', 'Batches created successfully!');
    }

    public function list(Request $request): Response
    {
        $schoolYearId = $request->input('school_year_id');

        if (! $schoolYearId) {
            $batches = Batch::with('level', 'schoolYear')->get();

            return Inertia::render('Welcome', [
                'batches' => $batches,
            ]);
        }
        $batches = Batch::with('level', 'schoolYear')
            ->where('school_year_id', $schoolYearId)
            ->get();

        return Inertia::render('Welcome', [
            'batches' => $batches,
        ]);
    }

    public function active(): RedirectResponse|Response
    {
        $schoolYear = SchoolYear::getActiveSchoolYear();

        if (! $schoolYear) {
            return redirect()->back()->withErrors(['school_year' => 'Active school year not found!']);
        }

        return Inertia::render('Admin/GettingStarted/AssignSubjects', [
            'batches' => Batch::active(['level', 'schoolYear']),
        ]);
    }

    public function show(Batch $batch): Response
    {
        $batch->load(['level', 'schoolYear', 'schedule' => function ($query) {
            $query->with('batchSubject', 'schoolPeriod');
        }]);

        $batchSchedules = BatchSchedule::whereHas('sessions', function ($query) {
            $query->whereDate('date', Carbon::today());
        })->with(['sessions' => function ($query) {
            $query
                ->whereDate('date', Carbon::today())
                ->with('batchSubject.subject', 'schoolPeriod', 'teacher.user');
        }])->where('batch_id', $batch->id)
            ->get();

        $batches = Batch::where('level_id', $batch->level_id)
            ->where('school_year_id', $batch->school_year_id)
            ->where('id', '=', $batch->id)
            ->get();

        $selectedBatch = $batches->each(function ($batch) {
            $batchStudentIds = $batch->load('students')->students->pluck('student_id');

            $batch->top_students = StudentService::getBatchTopStudents($batchStudentIds);
            $batch->bottom_students = StudentService::getBatchBottomStudents($batchStudentIds);
            $batch->grade = $batch->grades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first();
        });

        $students = Student::whereHas('batches', function ($query) use ($batch) {
            $query->where('batch_id', $batch->id);
        })->with('user')->paginate(10);

        $assessments = Assessment::wherehas('batchSubject', function ($query) use ($batch) {
            $query->where('batch_id', $batch->id);
        })->where('status', Assessment::STATUS_SCHEDULED)
            ->with('assessmentType', 'batchSubject.subject', 'teacher.user')
            ->get();

        $flaggedStudents = Flag::whereHas('batchSubject.subject', function ($query) use ($batch) {
            $query->where('batch_id', $batch->id);
        })->with('flaggedBy', 'flaggable.user.admin')->paginate(10);

        $studentsNotes = StudentNote::whereHas('student', function ($query) use ($batch) {
            $query->whereHas('batches', function ($query) use ($batch) {
                $query->where('batch_id', $batch->id);
            });
        })->with('author', 'student.user')
            ->get();

        return Inertia::render('Admin/Batches/Index', [
            'batch' => $batch,
            'active_session' => $batch->activeSession,
            'selected_batch' => $selectedBatch,
            'batch_schedules' => $batchSchedules,
            'students' => $students,
            'assessments' => $assessments,
            'flags' => $flaggedStudents,
            'students_notes' => $studentsNotes,
        ]);
    }
}
