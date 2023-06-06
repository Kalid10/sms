<?php

namespace App\Http\Controllers\Web;

use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Semester;
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
            ->where('status', '!=', Assessment::STATUS_DRAFT)
            ->where('quarter_id', Quarter::getActiveQuarter()->id)
            ->orderBy('updated_at', 'desc')
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
            'batch_subject_grade' => $batchSubject->batchGrades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first(),
        ]);
    }

    public function student(Student $student, Request $request): Response
    {
        $batchSubjectId = $request->query('batch_subject_id');

        $student = $student->load('user');
        $currentBatch = $student->activeBatch(['level', 'subjects']);

        $studentBatchSubjectIds = collect($currentBatch['subjects'])->pluck('id');
        $commonBatchSubject = BatchSubject::where('teacher_id', auth()->user()->teacher->id)
            ->whereIn('id', $studentBatchSubjectIds)
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')->get();

        $studentAssessment = $student->assessments()->orderBy('updated_at', 'DESC')
            ->whereRelation('assessment', 'batch_subject_id', $batchSubjectId)->get()->map(function ($studentAssessment) {
                $studentAssessment->assessment->point = $studentAssessment->point;

                return $studentAssessment->assessment;
            })->take(4);

        $student->conduct = $student->studentSubjectGrades()->where(
            'batch_subject_id',
            $batchSubjectId
        )->where('gradable_type', Quarter::class)->first()?->conduct;

        $student->absentee_percentage = $student->absenteePercentage();
        $student->assessment_quarter_grade = $student->fetchAssessmentsGrade($batchSubjectId, Quarter::getActiveQuarter()->id);
        $student->total_batch_subject_grade = $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first()?->score;
        $student->batch_subject_rank = $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first()?->rank;
        $student->quarterly_grade = $student->grades()->where([[
            'gradable_type', Quarter::class,
        ], [
            'gradable_id', Quarter::getActiveQuarter()->id,
        ]])->first();
        $student->semester_grade = $student->grades()->where([[
            'gradable_type', Semester::class,
        ], [
            'gradable_id', Semester::getActiveSemester()->id,
        ]])->first();

        return Inertia::render('Teacher/Student', [
            'student' => $student->load('user'),
            'guardian' => $student->load(
                'guardian:id,user_id',
                'guardian.user:id,name,email,phone_number,address_id',
                'guardian.user.address:id,sub_city,city,country,woreda,house_number',
            ),
            'assessments' => $studentAssessment,
            'current_batch' => $currentBatch,
            'attendance_percentage' => 100 - $student->absenteePercentage(),
            'schedule' => $student->activeBatch()->load(
                'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
                'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
                'schedule.batchSubject.subject',
                'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id'
            )->only('schedule')['schedule'],
            'periods' => Level::find($student->activeBatch()->level->id)->levelCategory->schoolPeriods,
            'batch_sessions' => $student->upcomingSessions(['batchSchedule.batchSubject.batch.level', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.subject', 'batchSchedule.batchSubject.teacher.user'])->get(),
            'batch_subject' => BatchSubject::find($batchSubjectId)->load('subject', 'batch.level'),
            'batch_subjects' => $commonBatchSubject,
            'batch_subject_grade' => $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first(),
            'total_batch_students' => $student->activeBatch()->students()->count(),
            'in_progress_session' => $currentBatch->inProgressSession()?->load('batchSchedule.batchSubject.subject', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.teacher.user'),
            'student_notes' => $student->notes()->orderBy('updated_at', 'DESC')->with('author:name,id,email,phone_number,gender')->get()->take(5),
        ]);
    }

    public function students(Request $request): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'search' => 'nullable|string',
        ]);
        $teacher = Teacher::find(auth()->user()->teacher->id);
        $batchSubjectId = $request->input('batch_subject_id');
        $search = $request->input('search');

        $batchSubject = $batchSubjectId ?
            BatchSubject::find($request->input('batch_subject_id'))->load('subject', 'batch.level') :
            BatchSubject::where('teacher_id', auth()->user()->teacher->id)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()->load('subject', 'batch.level');

        $batchStudents = BatchStudent::where('batch_id', $batchSubject->batch_id)
            ->with('student.user', 'student.batches')
            ->whereHas('student.user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate(10);

        $batchStudents->getCollection()->transform(function ($student) use ($batchSubject) {
            $studentBatchSubjectGrade = $student->student->fetchStudentBatchSubjectGrade($batchSubject->id, Quarter::getActiveQuarter()->id)->first();
            $student->attendance_percentage = 100 - $student->student->absenteePercentage();
            $student->batch_subject_rank = $studentBatchSubjectGrade?->rank;
            $student->conduct = $studentBatchSubjectGrade?->conduct;
            $student->quarterly_grade = $student->student->grades()->where([[
                'gradable_type', Quarter::class,
            ], [
                'gradable_id', Quarter::getActiveQuarter()->id,
            ]])->first();
            $student->semester_grade = $student->student->grades()->where([[
                'gradable_type', Semester::class,
            ], [
                'gradable_id', Semester::getActiveSemester()->id,
            ]])->first();

            return $student;
        });

        $batchSubjects = $teacher->batchSubjects()
            ->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')
            ->get();

        return Inertia::render('Teacher/Students', [
            'students' => $batchStudents,
            'batch_subject' => $batchSubject,
            'batch_subjects' => $batchSubjects,
            'search' => $search,
            'batch_subject_grade' => $batchSubject->batchGrades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first(),
        ]);
    }
}
