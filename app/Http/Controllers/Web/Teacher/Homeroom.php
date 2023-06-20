<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\Quarter;
use App\Models\Teacher;
use App\Models\User;
use App\Services\StudentService;
use App\Services\TeacherService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Homeroom extends Controller
{
    protected TeacherService $teacherService;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'batch_id' => 'nullable|integer|exists:batches,id',
            'search' => 'nullable|string',
            'student_id' => 'nullable|exists:students,id',
            'teacher_id' => 'nullable|integer|exists:teachers,id',
        ]);

        $teacher = auth()->user()->isTeacher() ? auth()->user()->teacher : Teacher::findOrFail($request->input('teacher_id'))->load('user', 'homeroom.batch');

        if (! $teacher) {
            abort(403);
        }

        $homeroomClasses = $teacher->load(['homeroom.batch.level', 'homeroom.batch.students', 'homeroom.batch.grades' => function ($query) {
            return $query->where('gradable_type', Quarter::class)
                ->where('gradable_id', Quarter::getActiveQuarter()->id)->first();
        }])->homeroom;
        $batchId = $request->input('batch_id') ?? $homeroomClasses[0]->batch_id ?? null;
        $batch = \App\Models\Batch::find($batchId);
        $search = $request->input('search');

        $batchStudents = $batch?->load('students')->students->pluck('student_id');

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Homeroom',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'homeroom_classes' => $homeroomClasses,
            'students' => $batchId ? StudentService::getBatchStudents($batchId, $search) : null,
            'top_students' => $batchStudents ? StudentService::getBatchTopStudents($batchStudents) : [],
            'bottom_students' => $batchStudents ? StudentService::getBatchBottomStudents($batchStudents) : [],
            'grade' => $batch ? $batch->grades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first() : null,
            'student.grades' => StudentService::getStudentDetail($request->input('student_id'), $batch),
            'teacher' => $teacher,
            'batches' => TeacherService::assignHomeroomTeacherData(),
            'filters' => [
                'batch_id' => $batchId,
                'search' => $search,
            ],
        ]);
    }
}
