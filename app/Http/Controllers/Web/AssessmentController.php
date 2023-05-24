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

    public function mark(Request $request, Assessment $assessment): Response|RedirectResponse
    {
        $request->validate([
            'student_id' => 'nullable|integer|exists:students,id',
        ]);

        // If status is published, change to marking
        if ($assessment->status === Assessment::STATUS_PUBLISHED) {
            $assessment->update(['status' => Assessment::STATUS_MARKING]);
        }
        if ($assessment->status !== Assessment::STATUS_MARKING) {
            return redirect()->back()->with('error', 'Invalid assessment type.');
        }

        $student = null;
        if ($request->input('student_id')) {
            $student = Student::find($request->input('student_id'))->load('user:id,name');
            $student->absentee_percentage = $student->absenteePercentage();
        }

        return Inertia::render('Teacher/Assessments/Mark', [
            'assessment' => $assessment->load('assessmentType:id,name', 'batchSubject:id,batch_id,subject_id',
                'batchSubject.subject:id,full_name', 'batchSubject.batch:id,section,level_id', 'batchSubject.batch.level:id,name',
                'students:id,student_id,assessment_id,point',
                'students.student:id,user_id', 'students.student.user:id,name'),
            'student' => $student,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'assessment_id' => 'required|integer|exists:assessments,id',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'maximum_point' => 'nullable|integer',
            'status' => 'nullable|string|in:draft,published,closed,marking,completed',
        ]);

        $assessment = Assessment::find($validatedData['assessment_id']);
        $assessment->update($validatedData);

        return redirect()->back()->with('success', 'Assessment updated successfully!');
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
            ->with([
                'batchSubject:id,batch_id,subject_id,teacher_id',
                'batchSubject.batch:id,section,level_id',
                'batchSubject.batch.level:id,name',
                'batchSubject.subject:id,full_name',
                'assessmentType:id,name',
                'quarter:id,name',
                'students',
            ])
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        $assessments->each(function ($assessment) {
            if ($assessment->status === Assessment::STATUS_PUBLISHED) {
                $assessment->total_students = $assessment->students->count();
            }

            if ($assessment->status === Assessment::STATUS_COMPLETED) {
                $assessment->averageScore = $assessment->students->avg('point');
                $assessment->passingPercentage = $assessment->students->filter(function ($student) {
                    return $student->point >= GradeScale::where([
                        ['school_year_id' => SchoolYear::getActiveSchoolYear()->id],
                        ['state' => 'pass'],
                    ])->first()->minimum_score;
                })->count() / max($assessment->students->count(), 1) * 100;
            }
        });

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
            ],
        ]);
    }
}
