<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\Quarter;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Homeroom extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'batch_id' => 'nullable|integer|exists:batches,id',
            'search' => 'nullable|string',
            'student_id' => 'nullable|exists:students,id',
        ]);

        $homeroomClasses = auth()->user()->teacher->load(['homeroom.batch.level', 'homeroom.batch.students', 'homeroom.batch.grades' => function ($query) {
            return $query->where('gradable_type', Quarter::class)
                ->where('gradable_id', Quarter::getActiveQuarter()->id)->first();
        }])->homeroom;
        $batchId = $request->input('batch_id') ?? $homeroomClasses[0]->batch_id ?? null;
        $batch = \App\Models\Batch::find($batchId);
        $search = $request->input('search');

        $batchStudents = $batch?->load('students')->students->pluck('student_id');

        return Inertia::render('Teacher/Homeroom', [
            'homeroom_classes' => $homeroomClasses,
            'students' => $batchId ? StudentService::getBatchStudents($batchId, $search) : null,
            'top_students' => $batchStudents ? StudentService::getBatchTopStudents($batchStudents) : [],
            'bottom_students' => $batchStudents ? StudentService::getBatchBottomStudents($batchStudents) : [],
            'grade' => $batch ? $batch->grades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first() : null,
            'student' => StudentService::getStudentDetail($request->input('student_id'), $batch),
            'filters' => [
                'batch_id' => $batchId,
                'search' => $search,
            ],
        ]);
    }
}
