<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Students\Request;
use App\Http\Requests\BatchSubjectGradeRequest;
use App\Http\Resources\Grade\ReportResource;
use App\Http\Resources\Grade\SubjectGradeResource;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentSubjectGrade;

class GradeController extends Controller
{
    public function index(Request $request, Student $student): ReportResource
    {
        $studentGrade = StudentGrade::where([
            'student_id' => $student->id,
            'gradable_type' => $request->input('gradable_type', 'App\Models\Quarter'),
            'gradable_id' => $request->input('id', Quarter::getActiveQuarter()->id),
        ])->with([
            'gradable',
            'gradeScale',
            'student',
        ])->first();

        $studentSubjectGrades = StudentSubjectGrade::where([
            'student_id' => $student->id,
            'gradable_type' => $request->input('gradable_type', 'App\Models\Quarter'),
            'gradable_id' => $request->input('id', Quarter::getActiveQuarter()->id),
        ])->with('gradable', 'gradeScale', 'batchSubject.subject', 'batchSubject.teacher.user')->get();

        return new ReportResource([
            'grade' => $studentGrade,
            'subjects' => $studentSubjectGrades,
        ]);
    }

    public function batchSubject(BatchSubjectGradeRequest $request, BatchSubject $batchSubject, Student $student): SubjectGradeResource
    {
        $gradableId = $this->getActiveGradableId($request->input('gradable_type', 'App\Models\Quarter'));

        return new SubjectGradeResource(StudentSubjectGrade::where([
            'student_id' => $student->id,
            'batch_subject_id' => $batchSubject->id,
            'gradable_type' => $request->input('gradable_type', 'App\Models\Quarter'),
            'gradable_id' => $gradableId,
        ])->with([
            'gradable',
            'gradeScale',
            'student',
            'batchSubject',
        ])->first());
    }

    private function getActiveGradableId(string $gradableType): int
    {
        return match ($gradableType) {
            'App\Models\Semester' => Semester::getActiveSemester()->id,
            'App\Models\SchoolYear' => SchoolYear::getActiveSchoolYear()->id,
            default => Quarter::getActiveQuarter()->id,
        };
    }
}
