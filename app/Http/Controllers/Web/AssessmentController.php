<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Teachers\CreateAssessmentRequest;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\GradeScale;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Services\TeacherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssessmentController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function create(CreateAssessmentRequest $request): RedirectResponse
    {
        $request->validated();

        Assessment::create(array_merge(
            $request->validated(),
            ['quarter_id' => Quarter::getActiveQuarter()->id]
        ));

        return redirect()->back()->with('success', 'Assessment created.');
    }

    public function update(Request $request): RedirectResponse
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

        return redirect()->back()->with('success', 'Assessment updated successfully!');
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
            $student = Student::find($request->input('student_id'))->load('user:id,name');
            $student->absentee_percentage = $student->absenteePercentage();
            $student->assessment_quarter_grade = $student->fetchAssessmentsGrade($assessment->batch_subject_id, Quarter::getActiveQuarter()->id);
            $student->total_batch_subject_grade = $student->fetchBatchSubjectGrade($assessment->batch_subject_id, Quarter::getActiveQuarter()->id)->first()?->score;
            $student->batch_subject_rank = $student->fetchBatchSubjectGrade($assessment->batch_subject_id, Quarter::getActiveQuarter()->id)->first()?->rank;
        }

        $assessment = $this->populateAssessmentDetails(collect([$assessment]))->first();

        return Inertia::render('Teacher/Assessments/Mark', [
            'assessment' => $assessment->load('assessmentType:id,name,percentage,min_assessments,max_assessments', 'batchSubject:id,batch_id,subject_id',
                'batchSubject.subject:id,full_name,short_name', 'batchSubject.batch:id,section,level_id', 'batchSubject.batch.level:id,name',
                'students:id,student_id,assessment_id,point,comment',
                'students.student:id,user_id', 'students.student.user:id,name'),
            'student' => $student,
        ]);
    }

    public function detail(Assessment $assessment): Response
    {
        $assessment = $this->populateAssessmentDetails(collect([$assessment]))->first();

        $completedAssessments = Assessment::where([
            ['status', Assessment::STATUS_COMPLETED],
            ['quarter_id', Quarter::getActiveQuarter()->id],
            ['batch_subject_id', $assessment->batch_subject_id],
            ['assessment_type_id', $assessment->assessment_type_id],
        ])->get();

        $assessment->load('assessmentType:id,name,percentage,min_assessments,max_assessments,customizable', 'batchSubject:id,batch_id,subject_id',
            'batchSubject.subject:id,full_name,short_name', 'batchSubject.batch:id,section,level_id', 'batchSubject.batch.level:id,name',
            'students:id,student_id,assessment_id,point,comment',
            'students.student:id,user_id', 'students.student.user:id,name');

        $assessment->assessment_type_points_sum = $completedAssessments->sum('maximum_point');
        $assessment->assessment_type_completed_count = $completedAssessments->count();

        return Inertia::render('Teacher/Assessments/Index', [
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
        ]);

        $batchSubjectId = $request->input('batch_subject_id') ??
            BatchSubject::where('teacher_id', auth()->user()->teacher->id)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()->id;

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

        return Inertia::render('Teacher/Assessments/Index', [
            'assessments' => $assessments,
            'teacher' => $this->teacherService->getTeacherDetails(auth()->user()->teacher->id),
            'assessment_type' => AssessmentType::all(),
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
}
