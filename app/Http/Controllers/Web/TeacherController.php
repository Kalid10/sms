<?php

namespace App\Http\Controllers\Web;

use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\Flag;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Services\StudentService;
use App\Services\TeacherService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    protected StudentService $studentService;

    public function __construct(TeacherService $teacherService, StudentService $studentService)
    {
        $this->teacherService = $teacherService;
        $this->studentService = $studentService;
    }

    public function index(Request $request): Response
    {
        $searchKey = $request->input('search');

        $perPage = $request->input('per_page', 15);

        $teachers = Teacher::with([
            'user:id,name,email,phone_number,gender',
            'batchSubjects:id,subject_id,batch_id,teacher_id',
            'batchSubjects.subject:id,full_name',
            'batchSubjects.batch:id,section,level_id',
            'batchSubjects.batch.level:id,name',
            'homeroom:id,batch_id,teacher_id',
            'homeroom.batch:id,section,level_id',
            'homeroom.batch.level:id,name',
            'batchSessions.teacher.user',
            'batchSessions' => function ($query) {
                $query->where('status', 'in_progress');
            },
            'batchSessions.batchSchedule.batchSubject.subject',
            'batchSessions.batchSchedule.batch.level',

        ])->select('id', 'user_id')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })
            ->when($request->input('subject_id'), function ($query) use ($request) {
                return $query->whereHas('batchSubjects', function ($query) use ($request) {
                    return $query->where('subject_id', $request->input('subject_id'));
                });
            })
            ->when($request->input('level_id'), function ($query) use ($request) {
                return $query->whereHas('batchSubjects', function ($query) use ($request) {
                    return $query->whereHas('batch', function ($query) use ($request) {
                        return $query->where('level_id', $request->input('level_id'));
                    });
                });
            })
            ->paginate($perPage);

        // Subjects of active school year
        $subjects = Subject::select('id', 'full_name')
            ->whereHas('batches', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()?->id);
            })
            ->get();

        // Active school year batches with level
        $batches = Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->with('level:id,name')->get();

        // Active school year levels
        $levels = $batches->pluck('level')->unique('id')->values();

        return Inertia::render('Admin/Teachers/Index', [
            'teachers' => $teachers,
            'subjects' => $subjects,
            'levels' => $levels,
            'filters' => [
                'search_key' => $searchKey,
            ],
        ]);
    }

    public function show(Request $request, int $id = null): Response
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $id = auth()->user()->isTeacher() ? auth()->user()->teacher->id : $id ?? $request->input('teacher_id');
        if (! $id) {
            abort(403);
        }

        $schoolYearId = SchoolYear::getActiveSchoolYear()?->id;
        $batchSubject = $this->teacherService->prepareBatchSubject($request, $id);
        $batches = $this->teacherService->getBatches($id);
        $students = $this->teacherService->getStudents($batchSubject->id, $request->input('search'), $request);
        $teacher = $this->teacherService->getTeacherDetails($id);
        $teacherBatchSubjects = $teacher->batchSubjects->pluck('id');
        $teacherSchedules = BatchSchedule::whereIn('batch_subject_id', $teacherBatchSubjects)
            ->where([['day_of_week', Carbon::now()->subDays(4)->dayOfWeek]])
            ->with('batchSubject.subject', 'schoolPeriod', 'batch.level')
            ->get()
            ->sortBy(function ($schedule) {
                return (int) $schedule->schoolPeriod->name;
            })->values();

        if (isset($teacher->nextBatchSession)) {
            // Get the last assessment of the next batch session using the batch subject id
            $lastAssessment = Assessment::where('batch_subject_id', $teacher->nextBatchSession->batchSubject->id)
                ->where('quarter_id', Quarter::getActiveQuarter()->id)
                ->orderBy('created_at', 'desc')
                ->where('status', '!=', Assessment::STATUS_DRAFT)
                ->first();

            if ($lastAssessment) {
                $lastAssessment->load('assessmentType:id,name');
            }
        }

        $schoolScheduleDate = $request->input('school_schedule_date') ?? now();
        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        $announcements = Announcement::where('school_year_id', $schoolYearId)
            ->where(function ($query) {
                $query->whereJsonContains('target_group', 'all')
                    ->orWhereJsonContains('target_group', 'teachers');
            })
            ->with('author.user')
            ->orderBy('updated_at', 'DESC')
            ->take(4)
            ->get();

        $flags = Flag::whereIn('batch_subject_id', $teacherBatchSubjects)->with(['flaggedBy', 'flaggable.user.admin', 'batchSubject.subject',
        ])->latest('updated_at')->paginate(7);

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Index',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        $teacherAbsenteeCount = $this->teacherService->getTeacherAbsenteeCount($id, $schoolYearId);

        $teacherSessionsCount = $this->teacherService->getTeacherSessionsCount($id, $schoolYearId);

        $teacherAbsenteePercentage = $teacherSessionsCount ? round(($teacherAbsenteeCount / $teacherSessionsCount) * 100, 2) : 0;

        return Inertia::render($page, [
            'teacher' => $teacher,
            'batches' => $batches,
            'assessment_type' => $batches->unique()->pluck('level.levelCategory.assessmentTypes')->unique()->flatten(),
            'students' => $students,
            'school_schedule' => $schoolSchedule,
            'school_schedule_date' => $schoolScheduleDate,
            'last_assessment' => $lastAssessment ?? null,
            'batch_subject' => $batchSubject,
            'teacher_schedule' => $teacherSchedules,
            'announcements' => $announcements,
            'flags' => $flags,
            'filters' => [
                'batch_subject_id' => $batchSubject->id,
                'search' => $request->input('search'),
            ],
            'teacher_absentee_percentage' => $teacherAbsenteePercentage,
        ]);
    }

    public function schedule(Request $request): Response
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'search' => 'nullable|string',
        ]);
        $searchKey = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()?->id)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('start_date', '>=', Carbon::parse($startDate));
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('end_date', '<=', Carbon::parse($endDate));
            })
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })
            ->orderBy('start_date', 'asc')
            ->paginate(7)->appends(request()->query());

        return Inertia::render('Teacher/SchoolSchedule', [
            'school_schedule' => $schoolSchedule,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $searchKey,
            ],
        ]);
    }
}
