<?php

namespace App\Http\Controllers\Web\Assessments;

use App\Http\Controllers\Web\Controller;
use App\Http\Requests\Teachers\CreateAssessmentRequest;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\GradeScale;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use App\Services\TeacherService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherAssessmentController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function create(CreateAssessmentRequest $request): Response
    {
        $request->validated();

        foreach ($request->input('batch_subject_ids') as $batchSubjectId) {
            $batchSubject = BatchSubject::find($batchSubjectId);
            $batchSubject->assessments()->create(array_merge(
                $request->validated(),
                ['quarter_id' => Quarter::getActiveQuarter()->id],
                ['batch_subject_id' => $batchSubjectId],
                ['created_by' => auth()->user()->id],
            ));
        }

        return $this->teacherAssessments(new Request());
    }

    public function update(Request $request): Response
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

        return $this->teacherAssessments(new Request());
    }

    public function delete(Assessment $assessment, Request $request): RedirectResponse
    {
        $confirmation = $request->query('confirmation');

        if ($confirmation !== $assessment->title) {
            return redirect()->back()->with('error', 'Incorrect confirmation text.');
        }
        if ($assessment->status === Assessment::STATUS_COMPLETED || $assessment->status === Assessment::STATUS_MARKING) {
            return redirect()->back()->with('error', 'Cannot delete '.$assessment->status.' assessment.');
        }

        $assessment->delete();

        return redirect()->to(route('teacher.assessment.teacher'))->with('success', 'Assessment deleted successfully!');
    }

    public function mark(Request $request, Assessment $assessment): Response|RedirectResponse
    {
        $request->validate([
            'student_id' => 'nullable|integer|exists:students,id',
        ]);

        // If status is published, change to marking
        if ($assessment->status === Assessment::STATUS_PUBLISHED) {
            $assessment->update(['status' => Assessment::STATUS_MARKING]);
        }
        if ($assessment->status !== Assessment::STATUS_MARKING && $assessment->status !== Assessment::STATUS_COMPLETED) {
            return redirect()->back()->with('error', 'Invalid assessment type.');
        }

        $student = null;
        if ($request->input('student_id')) {
            $student = Student::find($request->input('student_id'))->load('user:id,name,username');
            $student->absentee_percentage = $student->absenteePercentage();
            $student->assessment_quarter_grade = $student->fetchAssessmentsGrade($assessment->batch_subject_id, Quarter::getActiveQuarter()->id);
            $student->total_batch_subject_grade = $student->fetchStudentBatchSubjectGrade($assessment->batch_subject_id, Quarter::getActiveQuarter()->id)->first()?->score;
            $student->batch_subject_rank = $student->fetchStudentBatchSubjectGrade($assessment->batch_subject_id, Quarter::getActiveQuarter()->id)->first()?->rank;
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
        }

        $assessment = $this->populateAssessmentDetails(collect([$assessment]))->first();

        return Inertia::render('Teacher/Assessments/Mark', [
            'assessment' => $assessment->load('assessmentType:id,name,percentage,min_assessments,max_assessments', 'batchSubject:id,batch_id,subject_id',
                'batchSubject.subject:id,full_name,short_name', 'batchSubject.batch:id,section,level_id', 'batchSubject.batch.level:id,name',
                'students:id,student_id,assessment_id,point,comment,status',
                'students.student:id,user_id', 'students.student.user:id,name,username',
                'createdBy:id,name'),
            'student' => $student,
        ]);
    }

    public function detail($id): RedirectResponse|Response
    {
        $assessment = Assessment::find($id);
        if (! $assessment) {
            return redirect()->to(route('teacher.assessment.teacher'));
        }
        $assessment = $this->populateAssessmentDetails(collect([$assessment]))->first();

        $completedAssessments = Assessment::where([
            ['status', Assessment::STATUS_COMPLETED],
            ['quarter_id', Quarter::getActiveQuarter()->id],
            ['batch_subject_id', $assessment->batch_subject_id],
            ['assessment_type_id', $assessment->assessment_type_id],
        ])->get();

        $assessment->load('assessmentType:id,name,percentage,min_assessments,max_assessments,customizable,is_admin_controlled', 'batchSubject:id,batch_id,subject_id',
            'batchSubject.subject:id,full_name,short_name', 'batchSubject.batch:id,section,level_id', 'batchSubject.batch.level:id,name',
            'students:id,student_id,assessment_id,point,comment,status',
            'students.student:id,user_id', 'students.student.user:id,name', 'createdBy:id,name');

        $assessment->assessment_type_points_sum = $completedAssessments->sum('maximum_point');
        $assessment->assessment_type_completed_count = $completedAssessments->count();

        return Inertia::render($this->getPage(), [
            'assessment' => $assessment,
        ]);
    }

    public function teacherAssessments(Request $request): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'assessment_type_id' => 'nullable|integer|exists:assessment_types,id',
            'due_date' => 'nullable|date',
            'quarter_id' => 'nullable|integer|exists:quarters,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'school_year_id' => 'nullable|integer|exists:school_years,id',
            'search' => 'nullable|string',
            'status' => 'nullable|string|in:draft,published,closed,marking,completed',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
        ]);

        $teacherId = auth()->user()->teacher->id ?? $request->input('teacher_id');
        if (! $teacherId) {
            abort(403);
        }

        $batchSubjectId = $request->input('batch_subject_id') ??
            BatchSubject::where('teacher_id', $teacherId)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()?->id;

        $quarters = Quarter::with('semester.schoolYear')->get();
        $semesters = Semester::with('schoolYear')->get();
        $schoolYears = SchoolYear::all();

        $schoolYearId = $request->input('school_year_id');
        $semesterId = $request->input('semester_id');
        $quarterId = $request->input('quarter_id');
        $search = $request->input('search');
        $dueDate = $request->input('due_date');

        $assessments = Assessment::where('batch_subject_id', $batchSubjectId)
            ->when($request->input('assessment_type_id'), function ($query, $value) {
                return $query->where('assessment_type_id', $value);
            })
            ->when($dueDate, function ($query, $value) {
                return $query->whereDate('due_date', $value);
            })
            ->when($quarterId, function ($query, $value) {
                return $query->where('quarter_id', $value);
            })
            ->when($semesterId, function ($query, $value) {
                return $query->whereHas('quarter', function ($query) use ($value) {
                    return $query->where('semester_id', $value);
                });
            })
            ->when($schoolYearId, function ($query, $value) {
                return $query->whereHas('quarter.semester', function ($query) use ($value) {
                    return $query->where('school_year_id', $value);
                });
            })
            ->when($search, function ($query, $value) {
                return $query->where('title', 'like', "%{$value}%");
            })
            ->when($request->input('status'), function ($query, $value) {
                return $query->where('status', $value);
            })
            ->with([
                'batchSubject:id,batch_id,subject_id,teacher_id',
                'batchSubject.batch:id,section,level_id',
                'batchSubject.batch.level:id,name',
                'batchSubject.subject:id,full_name',
                'assessmentType:id,name,percentage,min_assessments,max_assessments',
                'quarter:id,name',
                'students',
            ])
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return Inertia::render($this->getPage(), [
            'assessments' => $assessments,
            'teacher' => $this->teacherService->getTeacherDetails($teacherId),
            'assessment_type' => AssessmentType::where('is_admin_controlled', false)->get(),
            'quarters' => $quarters,
            'semesters' => $semesters,
            'school_years' => $schoolYears,
            'filters' => [
                'school_year_id' => $schoolYearId,
                'semester_id' => $semesterId,
                'quarter_id' => $quarterId,
                'search' => $search,
                'due_date' => $dueDate,
                'batch_subject_id' => $batchSubjectId,
                'assessment_type_id' => $request->input('assessment_type_id'),
                'status' => $request->input('status'),
            ],
        ]);
    }

    private function populateAssessmentDetails($assessments)
    {
        $assessments->each(function ($assessment) {
            if ($assessment->students()->count() > 0) {
                $assessment->total_students = $assessment->students->count();
            }

            if ($assessment->status === Assessment::STATUS_COMPLETED) {
                // Get average score and passing percentage
                $assessment->average_score = round($assessment->students->avg('point'), 1);
                $assessment->passing_percentage = round($assessment->students->filter(function ($student) use ($assessment) {
                    return $student->point >= GradeScale::get($student->point, $assessment->maximum_point)->minimum_score;
                })->count() / max($assessment->students->count(), 1) * 100, 1);

                // Get highest and lowest score
                $assessment->highest_score = $assessment->students->max('point');
                $assessment->lowest_score = $assessment->students->min('point');

                // Get top and bottom 3 students
                $assessment->top_students = $assessment->students->sortByDesc('point')->values()->load('student.user')->take(4);
                $assessment->bottom_students = $assessment->students->sortBy('point')->values()->load('student.user')->take(4);
            }
        });

        return $assessments;
    }

    private function getPage(): string
    {
        return match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Assessments/Index',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };
    }
}
