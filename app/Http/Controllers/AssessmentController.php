<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teachers\CreateAssessmentRequest;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
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
        $validated = $request->validated();

        Assessment::create(array_merge(
            $request->validated(),
            ['quarter_id' => Quarter::getActiveQuarter()->id]
        ));

        return redirect()->back()->with('success', 'Assessment created.');
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
            ->when($request->input('assessment_type_id'), function ($query) use ($request) {
                return $query->where('assessment_type_id', $request->input('assessment_type_id'));
            })
            ->when($request->input('due_date'), function ($query) use ($request) {
                return $query->whereDate('due_date', $request->input('due_date'));
            })
            ->when($request->input('quarter_id'), function ($query) use ($request) {
                return $query->where('quarter_id', $request->input('quarter_id'));
            })
            ->when($request->input('semester_id'), function ($query) use ($request) {
                return $query->whereHas('quarter', function ($query) use ($request) {
                    return $query->where('semester_id', $request->input('semester_id'));
                });
            })
            ->when($request->input('school_year_id'), function ($query) use ($request) {
                return $query->whereHas('quarter.semester', function ($query) use ($request) {
                    return $query->where('school_year_id', $request->input('school_year_id'));
                });
            })
            ->when($request->input('search'), function ($query) use ($request) {
                return $query->where('title', 'like', "%{$request->input('search')}%");
            })
            ->with([
                'batchSubject:id,batch_id,subject_id,teacher_id',
                'assessmentType:id,name',
                'quarter:id,name',
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
            ],
        ]);
    }
}
