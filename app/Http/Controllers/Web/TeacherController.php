<?php

namespace App\Http\Controllers\Web;

use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\TeacherService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
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
        $id = $id ?? (auth()->user()->isTeacher() ? auth()->user()->teacher->id : abort(403));

        $batches = $this->teacherService->getBatches($id);
        $students = $this->teacherService->getStudents($id, $request->input('batch_subject_id'), $request->input('search'));
        $teacher = $this->teacherService->getTeacherDetails($id);

        if (isset($teacher->nextBatchSession)) {
            // Get the last assessment of the next batch session using the batch subject id
            $lastAssessment = Assessment::where('batch_subject_id', $teacher->nextBatchSession->batchSubject->id)
                ->where('quarter_id', Quarter::getActiveQuarter()->id)
                ->orderBy('created_at', 'desc')
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

        return Inertia::render('Teacher/Index', [
            'teacher' => $teacher,
            'batches' => $batches,
            'assessment_type' => $batches->unique()->pluck('level.levelCategory.assessmentTypes')->unique()->flatten(),
            'students' => $students,
            'school_schedule' => $schoolSchedule,
            'school_schedule_date' => $schoolScheduleDate,
            'last_assessment' => $lastAssessment ?? null,
        ]);
    }

    public function batch(Request $request): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'search' => 'nullable|string',
        ]);

        $search = $request->input('search');
        $batchSubjectId = $request->input('batch_subject_id');

        $batchSubject = $batchSubjectId ?
            BatchSubject::find($request->input('batch_subject_id'))->load('subject', 'batch.level') :
            BatchSubject::where('teacher_id', auth()->user()->teacher->id)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()->load('subject', 'batch.level');

        $students = $this->teacherService->getStudents(auth()->user()->teacher->id, $batchSubject->id, $search);

        // Get the teacher
        $teacher = Teacher::find(auth()->user()->teacher->id);
        $batchSubjects = $teacher->batchSubjects()
            ->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')
            ->get();

        // Get lesson plan of the teacher and filter it by batch subject
        $lessonPlan = $batchSubject->sessions()->whereHas('lessonPlan')->with('lessonPlan', 'batchSchedule.batch.level', 'batchSchedule.batchSubject.subject')->get()->take(4);

        // Get assessments of the teacher and filter it by batch subject
        $assessments = Assessment::where('batch_subject_id', $batchSubject->id)
            ->where('quarter_id', Quarter::getActiveQuarter()->id)
            ->with('assessmentType:id,name',
                'batchSubject.batch:id,section,level_id',
                'batchSubject.batch.level:id,name,level_category_id',
                'batchSubject.subject:id,full_name')
            ->get()->take(4);

        $batch = Batch::find($batchSubject->batch_id)->load('level:id,name,level_category_id', 'level.levelCategory:id,name');
        $schedule = Batch::find($batchSubject->batch_id)->load(
            'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
            'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
            'schedule.batchSubject.subject',
            'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id',
        )->only('schedule')['schedule'];

        return Inertia::render('Teacher/Batches', [
            'students' => $students[0]['students'],
            'batch_subject' => $batchSubject,
            'batch_subjects' => $batchSubjects,
            'search' => $search,
            'lesson_plans' => $lessonPlan,
            'assessments' => $assessments,
            'schedule' => $schedule,
            'periods' => Level::find($batch->level->id)->levelCategory->schoolPeriods,
        ]);
    }

    public function student(Student $student): Response
    {
        $student = $student->load('user');
        $currentBatch = $student->activeBatch(['level']);

        $studentAssessment = $student->assessments()->get()->map(function ($studentAssessment) {
            $studentAssessment->assessment->point = $studentAssessment->point;

            return $studentAssessment->assessment;
        })->take(4);

        return Inertia::render('Teacher/Student', [
            'student' => $student->load('user'),
            'assessments' => $studentAssessment,
            'current_batch' => $currentBatch,
            'absentee' => $student->absenteePercentage(),
            'schedule' => $student->activeBatch()->load(
                'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
                'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
                'schedule.batchSubject.subject',
                'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id'
            )->only('schedule')['schedule'],
            'periods' => Level::find($student->activeBatch()->level->id)->levelCategory->schoolPeriods,
            'batch_sessions' => $student->upcomingSessions(['batchSchedule.batchSubject.batch.level', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.subject', 'batchSchedule.batchSubject.teacher.user'])->get(),
            'batch_subject_rank' => $student->fetchBatchSubjectGrade(1443, Quarter::getActiveQuarter()->id)->first()?->rank,
        ]);
    }

    public function students(Request $request): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'search' => 'nullable|string',
        ]);
        $students = Student::whereHas('batches', function ($query) {
            $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
        })->with('user:id,first_name,last_name')->get();

        return Inertia::render('Teacher/Students', [
            'students' => $students,
        ]);
    }
}
