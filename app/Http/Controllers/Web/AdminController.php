<?php

namespace App\Http\Controllers\Web;

use App\Models\Absentee;
use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentMapping;
use App\Models\AssessmentType;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Flag;
use App\Models\Level;
use App\Models\LevelCategory;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\StaffAbsentee;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
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

    public function createAssessment(Request $request): RedirectResponse
    {
        $request->validate([
            'level_category_ids' => 'required|array',
            'level_category_ids.*' => 'required|integer|exists:level_categories,id',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'due_date' => 'required|date',
            'title' => 'required|string',
            'description' => 'string',
            'maximum_point' => 'required|integer|min:1|max:100',
            'lesson_plan_id' => 'nullable|exists:lesson_plans,id',
            'status' => 'nullable|string|in:draft,published,scheduled',
        ]);

        $levelCategoryIds = $request->input('level_category_ids');

        // Loop through each level category id and get level ids
        foreach ($levelCategoryIds as $levelCategoryId) {
            $levelIds = Level::where('level_category_id', $levelCategoryId)->pluck('id');

            // Loop through each level id and get batch ids
            foreach ($levelIds as $levelId) {
                $batchIds = Batch::where('level_id', $levelId)->pluck('id');

                // Loop through each batch id and get batch subject ids
                foreach ($batchIds as $batchId) {
                    $batchSubjectIds = BatchSubject::where('batch_id', $batchId)->pluck('id');

                    // Loop through each batch subject id and create assessment
                    foreach ($batchSubjectIds as $batchSubjectId) {
                        $batchSubject = BatchSubject::find($batchSubjectId);

                        $assessment = $batchSubject->assessments()->create(array_merge(

                            $request->only([
                                'assessment_type_id',
                                'due_date',
                                'title',
                                'description',
                                'maximum_point',
                                'lesson_plan_id',
                                'status',
                            ]),
                            [
                                'school_year_id' => SchoolYear::getActiveSchoolYear()->id,
                                'quarter_id' => Quarter::getActiveQuarter()->id,
                            ]
                        ));
                    }
                }
            }
            // Create an assessment mapping
            AssessmentMapping::create([
                'due_date' => Carbon::parse($request->input('due_date')),
                'user_id' => auth()->id(),
                'level_category_id' => $levelCategoryId,
                'assessment_type_id' => $request->input('assessment_type_id'),
            ]);
        }

        return redirect()->back()->with('success', 'Assessment created.');
    }

    public function updateAssessment(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'assessment_id' => 'required|integer|exists:assessments,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'maximum_point' => 'required|integer',
            'status' => 'required|string|in:draft,published,closed,marking,completed,scheduled',
            'due_date' => 'required|date',
        ]);

        $assessment = Assessment::find($validatedData['assessment_id']);
        $assessment->update($validatedData);

        return redirect()->back()->with('success', 'Assessment updated.');
    }

    public function deleteAssessment(Assessment $assessment): RedirectResponse
    {
        $assessment->delete();

        return redirect()->back()->with('success', 'Assessment deleted.');
    }
}
