<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\Batch;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Services\StudentService;
use App\Services\TeacherService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Students extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'search' => 'nullable|string',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
        ]);

        $teacher = auth()->user()->isTeacher() ? auth()->user()->teacher : Teacher::findOrFail($request->input('teacher_id'))->load('user');

        if (! $teacher) {
            abort(403);
        }

        $batchSubject = TeacherService::prepareBatchSubject($request, $teacher->id);
        $batchStudents = TeacherService::getStudents($batchSubject->id, $request->input('search'));

        $batchSubjects = $teacher->batchSubjects()
            ->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')
            ->get();

        $batchesCount = Batch::find($batchSubject->batch_id)->load('level')->level->batches()->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->count();

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Students',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'students' => $batchStudents,
            'batch_subject' => $batchSubject,
            'batch_subjects' => $batchSubjects,
            'batch_subject_grade' => $batchSubject->batchGrades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first(),
            'total_batches_count' => $batchesCount,
            'top_students' => StudentService::getBatchSubjectTopStudents($batchSubject),
            'bottom_students' => StudentService::getBatchSubjectBottomStudents($batchSubject),
            'teacher' => $teacher,
            'filters' => [
                'batch_subject_id' => $batchSubject->id,
                'search' => $request->input('search'),
            ],
        ]);
    }
}
