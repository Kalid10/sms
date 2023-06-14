<?php

namespace App\Http\Controllers\Web;

use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\BatchSchedule;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
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
        ])->select('id', 'user_id')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })
            ->paginate($perPage);

        return Inertia::render('Admin/Teachers/Index', [
            'teachers' => $teachers,
        ]);
    }

    public function show(Request $request, int $id = null): Response
    {
        $request->validate([
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        if (! auth()->user()->isTeacher() && ! $request->input('teacher_id') && ! $id) {
            abort(403);
        }

        $id = $id ?? (auth()->user()->isTeacher() ? auth()->user()->teacher->id : $request->input('teacher_id'));
        $schoolYearId = SchoolYear::getActiveSchoolYear()?->id;

        $batchSubject = $this->teacherService->prepareBatchSubject($request, $id);
        $batches = $this->teacherService->getBatches($id);
        $students = $this->teacherService->getStudents($batchSubject->id, $request->input('search'));
        $teacher = $this->teacherService->getTeacherDetails($id);
        $teacherBatchSubjects = $teacher->batchSubjects->pluck('id');
        $teacherSchedules = BatchSchedule::whereIn('batch_subject_id', $teacherBatchSubjects)
            ->where([['day_of_week', Carbon::now()->dayOfWeek]])
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
            ->whereDate('start_date', '<=', Carbon::parse($schoolScheduleDate))
            ->whereDate('end_date', '>=', Carbon::parse($schoolScheduleDate))
            ->orderBy('start_date', 'asc')
            ->take(2)
            ->get();

        $announcements = Announcement::where('school_year_id', $schoolYearId)
            ->where(function ($query) {
                $query->whereJsonContains('target_group', 'all')
                    ->orWhereJsonContains('target_group', 'teachers');
            })
            ->with('author.user')
            ->orderBy('updated_at', 'DESC')
            ->take(5)
            ->get();

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Index',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

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
            'filters' => [
                'batch_subject_id' => $batchSubject->id,
                'search' => $request->input('search'),
            ],
        ]);
    }
}
