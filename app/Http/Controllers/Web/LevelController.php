<?php

namespace App\Http\Controllers\Web;

use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Services\StudentService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LevelController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        // Overwrite the default error message
        $messages = [
            'name.unique' => 'Grade already exists',
        ];

        $request->validate([
            'name' => 'required|string|unique:levels,name',
        ], $messages);

        Level::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Level created successfully');
    }

    public function list(Request $request): Response
    {
        // Get search key
        $searchKey = $request->input('search');

        $levels = Level::with([
            'levelCategory',
            'batches' => function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            },
        ])->when($searchKey, function ($query, $searchKey) {
            $query->where('name', 'LIKE', "%{$searchKey}%");
        })->get();

        return Inertia::render('Admin/Levels/Index', [
            'levels' => $levels,
        ]);
    }

    public function show(Level $level, Request $request, Batch $batch): Response
    {
        // Get search key
        $searchKey = $request->input('search');

        // For paginated data, get page and perPage value
        $page = $request->input('page', 1);

        // For paginated data, get perPage value
        $perPage = $request->input('per_page', 10);

        // Get section filter
        $sectionFilter = $request->input('section');

        $level->load([
            'levelCategory',
            'activeBatches.schoolYear',
            'activeBatches.subjects.subject' => function ($query) {
                $query->withTrashed('subjects');
            },
            'activeBatches.subjects.teacher.user',
            'activeBatches' => function ($query) {
                $query
                    ->with('activeSession.batchSubject.subject')
                    ->with('activeSession.teacher.user')
                    ->with('homeRoomTeacher.teacher.user')
                    ->with('activeSession.schoolPeriod')
                    ->with('activeSession.absentees.user.student')
                    ->withCount('students', 'subjects');
            },
            'activeBatches.schedule' => function ($query) {
                $query->where([
                    ['day_of_week', Carbon::now()->dayOfWeek],
                ]
                )->whereHas('schoolPeriod', function ($query) {
                    $query->where('is_custom', false);
                })->with('schoolPeriod');
            },
            'activeBatches.schedule.batchSubject.subject',
        ])
            ->loadCount([
                'activeBatches',
            ])
            ->only('id', 'name', 'level_category_id', 'updated_at', 'batches', 'batches_count');

        $level->activeBatches->each(function ($batch) {
            $batchStudentIds = $batch->load('students')->students->pluck('student_id');
            $batch->top_students = StudentService::getBatchTopStudents($batchStudentIds);
            $batch->bottom_students = StudentService::getBatchBottomStudents($batchStudentIds);
            $batch->grade = $batch->grades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first();

            $batchSubjectIds = BatchSubject::where('batch_id', $batch->id)->pluck('id');
            $batch->assessments = Assessment::whereIn('batch_subject_id', $batchSubjectIds)
                ->where('quarter_id', Quarter::getActiveQuarter()->id)
                ->where('status', '!=', Assessment::STATUS_DRAFT)
                ->orderBy('updated_at', 'DESC')
                ->with('assessmentType', 'batchSubject.batch:id,section,level_id',
                    'batchSubject.batch.level:id,name,level_category_id',
                    'batchSubject.subject:id,full_name')
                ->paginate(4);
        });

        $students = BatchStudent::whereIn('batch_id', $level->batches->pluck('id'))
            ->with(
                'student:id,user_id',
                'student.user:id,name,email,username,phone_number,gender,date_of_birth',
                'batch:id,section')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('student.user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })
            ->when($sectionFilter, function ($query) use ($sectionFilter) {
                return $query->whereHas('batch', function ($query) use ($sectionFilter) {
                    return $query->where('section', $sectionFilter);
                });
            })->paginate(10);

        $students->getCollection()->transform(function ($batchStudent) {
            return [
                ...$batchStudent->student->user->toArray(),
                'student_id' => $batchStudent->student->id,
                'section' => $batchStudent->batch->section,
                'updated_at' => $batchStudent->updated_at->diffForHumans(Carbon::now()),
            ];
        });

        // Get announcement with "students" target group
        $announcements = Announcement::whereJsonContains('target_group', 'students')->with('author.user')->get();

        $levelAssessments = $this->getLevelAssessments($batch);

        return Inertia::render('Admin/Levels/Single', [
            'level' => $level,
            'batches' => $level->activeBatches,
            'students' => Inertia::lazy(fn () => $students),
            'school_year' => SchoolYear::getActiveSchoolYear(),
            'announcements' => $announcements,
            'level_assessments' => $levelAssessments,
        ]);
    }

    public function section(Level $level): Response
    {
        return Inertia::render('Admin/Levels/Section');
    }

    public function assessments(Batch $batch): Response
    {
        $levelAssessments = $this->getLevelAssessments($batch);

        return Inertia::render('Admin/Levels/Assessments',
            [
                'batch' => $batch->load('level'),
                'level_assessments' => $levelAssessments,
            ]);
    }

    private function getLevelAssessments(Batch $batch)
    {
        return Assessment::whereIn('batch_subject_id', BatchSubject::whereIn('batch_id',
            Batch::where('level_id', $batch->level_id)->pluck('id'))->pluck('id'))
            ->where('status', '!=', Assessment::STATUS_DRAFT)
            ->orderBy('updated_at', 'DESC')
            ->with('assessmentType', 'batchSubject.batch:id,section,level_id',
                'batchSubject.subject:id,full_name')
            ->paginate(10);
    }
}
