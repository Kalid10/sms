<?php

namespace App\Http\Controllers\Web;

use App\Models\Absentee;
use App\Models\Batch;
use App\Models\BatchSession;
use App\Models\BatchStudent;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $students = StudentService::getAllStudents($request);

        $studentsCount = Student::with('currentBatch')->count();

        $todayAbsentees = Absentee::whereDate('created_at', today())->with('user', 'batchSession')->get();

        $latestPeriodAbsentees = Absentee::whereHas('batchSession', function ($query) {
            $query->where('status', BatchSession::STATUS_IN_PROGRESS);
        })->with('user', 'batchSession')->get();

        // Get all batches
        $batches = Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->with('level', 'homeroomTeacher.teacher.user')->get();

        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        $batchId = $request->batch_id ?? Batch::active()->firstOrFail()->id;
        $selectedBatch = Batch::find($batchId)->load('level');

        $batchStudents = BatchStudent::where('batch_id', $batchId)->with([
            'student.user:id,name,username,phone_number,gender',
            'batch:id,section',
            'level',
        ])->paginate(15);

        $studentsHasAssessment = Student::whereHas('assessments')->get();

        return Inertia::render('Admin/Students/Index', [
            'students' => $students,
            'batches' => $batches,
            'selected_batch' => $selectedBatch,
            'batch_students' => $batchStudents,
            'students_count' => $studentsCount,
            'today_absentees' => $todayAbsentees,
            'latest_period_absentees' => $latestPeriodAbsentees,
            'students_has_assessment' => $studentsHasAssessment,
        ]);
    }

    public function updateConduct(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'conduct' => 'required|in:A,B,C,D,F',
            'batch_subject_id' => 'required|exists:batch_subjects,id',
        ]);

        $studentSubjectGrade = $student->studentSubjectGrades()
            ->where('batch_subject_id', $request->input('batch_subject_id'))
            ->first();

        $studentGrade = $student->grades()->first();

        if (! $studentGrade || ! $studentSubjectGrade) {
            return redirect()->back()->with('error', 'Currently you cannot update conduct for this subject');
        }

        $studentSubjectGrade->conduct = $request->input('conduct');
        $studentSubjectGrade->save();

        return redirect()->back()->with('success', 'Conduct updated successfully');
    }
}
