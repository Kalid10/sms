<?php

namespace App\Http\Controllers\Web;

use App\Models\Absentee;
use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentMapping;
use App\Models\AssessmentType;
use App\Models\Flag;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\StaffAbsentee;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function show(Request $request): Response
    {
        $searchKey = $request->input('search');

        // Get teachers, students, admins and subjects count
        $teachersCount = User::where('type', 'teacher')->count();
        $studentsCount = User::where('type', 'student')->count();
        $subjectsCount = Subject::all()->count();
        $adminsCount = User::where('type', 'admin')->count();

        $levels = Level::with([
            'levelCategory',
            'batches' => function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            },
        ])->get();

        $activeStudents = User::with('student.batches.activeBatch')->where('type', 'student')->count();

        // Get absentee records of active batch students
        $absenteeRecords = Absentee::with('user.student.batches.activeBatch')->count();

        // Get staff absentee records of teachers
        $teacherAbsenteeRecords = StaffAbsentee::with('user')->whereHas('user', function ($query) {
            $query->where('type', 'teacher');
        })->count();

        // Get staff absentee records of admin
        $adminAbsenteeRecords = StaffAbsentee::with('user')->whereHas('user', function ($query) {
            $query->where('type', 'admin');
        })->count();

        // Get admins of active batch
        $admins = User::with('roles')->where('type', 'admin')->get();

        $schoolYear = SchoolYear::getActiveSchoolYear();

        $announcements = Announcement::where('school_year_id', $schoolYear?->id)->with('author.user')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })->orderBy('updated_at', 'DESC')->get()->take(5);

        $scheduleStartDate = $request->input('start_date') ?? Carbon::today();
        $schoolScheduleEndDate = $request->input('end_date') ?? now()->addDays(4);
        $schoolSchedule = SchoolSchedule::where('school_year_id', $schoolYear?->id)
            ->whereDate('start_date', '>=', Carbon::parse($scheduleStartDate))
            ->whereDate('end_date', '<=', Carbon::parse($schoolScheduleEndDate))
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        $flags = Flag::with('flaggedBy', 'flaggable.user.admin', 'batchSubject.subject')->latest('updated_at')->paginate(7);

        return Inertia::render('Admin/Index', [
            'teachers_count' => $teachersCount,
            'students_count' => $studentsCount,
            'subjects_count' => $subjectsCount,
            'admins_count' => $adminsCount,
            'levels' => $levels,
            'active_students' => $activeStudents,
            'absentee_records' => $absenteeRecords,
            'admins' => $admins,
            'school_year' => $schoolYear,
            'announcements' => $announcements,
            'school_schedule' => $schoolSchedule,
            'students' => Inertia::lazy(fn () => Student::with([
                'user:id,name,email,phone_number,gender',
                'currentBatch.level',
            ])->select('id', 'user_id')
                ->when($searchKey, function ($query) use ($searchKey) {
                    return $query->whereHas('user', function ($query) use ($searchKey) {
                        return $query->where('name', 'like', "%{$searchKey}%");
                    });
                })
                ->orderBy('user_id', 'asc')->get()
                ->take(5)
            ),
            'flags' => $flags,
            'teacher_absentee_records' => $teacherAbsenteeRecords,
            'admin_absentee_records' => $adminAbsenteeRecords,
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

        return Inertia::render('Admin/Schedules/Index', [
            'school_schedule' => $schoolSchedule,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $searchKey,
            ],
        ]);
    }

    public function announcements(Request $request): Response
    {
        $searchKey = $request->input('search');

        $schoolYears = SchoolYear::all();

        $schoolYearId = $request->input('school_year_id');
        $expiresOn = $request->input('expires_on');

        $announcements = Announcement::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })
            ->when($schoolYearId, function ($query) use ($schoolYearId) {
                return $query->where('school_year_id', $schoolYearId);
            })
            ->when($expiresOn, function ($query) use ($expiresOn) {
                return $query->where('expires_on', $expiresOn);
            })
            ->orderBy('created_at', 'desc')
            ->with('author.user:id,name')
            ->paginate(10);

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
            'filters' => [
                'school_year_id' => $schoolYearId ?? SchoolYear::getActiveSchoolYear()->id,
                'expires_on' => $expiresOn,
                'school_years' => $schoolYears,
                'searchKey' => $searchKey,
            ],
        ]);
    }

    public function assessments(Request $request): Response
    {
        $request->validate([
            'search' => 'nullable|string',
            'level_id' => 'nullable|integer',
            'assessment_type_id' => 'nullable|integer',
            'subject_id' => 'nullable|integer',
        ]);

        $searchKey = $request->input('search');

        // Get all assessment types of active school year
        $assessmentTypes = AssessmentType::with('levelCategory', 'schoolYear')
            ->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->get();

        $levels = Level::all();

        // Get Subjects of active school year
        $subjects = Subject::with('batches.schoolYear')->whereHas('batches', function ($query) {
            $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
        })->get();

        $levelCategories = LevelCategory::all();

        $mappedAssessments = AssessmentMapping::with('user', 'levelCategory', 'assessmentType')
            ->paginate(10);

        return Inertia::render('Admin/Assessments/Index', [
            'assessment_types' => $assessmentTypes,
            'level_categories' => $levelCategories,
            'filters' => [
                'search' => $searchKey,
                'assessment_types' => $assessmentTypes,
                'levels' => $levels,
                'subjects' => $subjects,
                'assessment_type_id' => $request->input('assessment_type_id') ?? null,
                'level_id' => $request->input('level_id') ?? null,
                'subject_id' => $request->input('subject_id') ?? null,
            ],
            'mapped_assessments' => $mappedAssessments,
        ]);
    }

    public function assessment(Assessment $assessment): Response
    {
        $assessment->load('batchSubject.level', 'assessmentType', 'quarter', 'batchSubject.subject');

        return Inertia::render('Admin/Assessments/Details', [
            'assessment' => $assessment,
        ]);
    }
}
