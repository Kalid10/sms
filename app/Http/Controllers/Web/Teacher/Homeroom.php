<?php

namespace App\Http\Controllers\Web\Teacher;

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
        ]);

        $homeroomClasses = auth()->user()->teacher->load('homeroom.batch.level', 'homeroom.batch.students', 'homeroom.batch.grades')->homeroom;
        $batchId = $request->input('batch_id') ?? $homeroomClasses[0]->batch_id;
        $search = $request->input('search');

        return Inertia::render('Teacher/Homeroom', [
            'homeroom_classes' => $homeroomClasses,
            'students' => StudentService::getBatchStudents($batchId, $search),
            'top_students' => [],
            'bottom_students' => [],
            'filters' => [
                'batch_id' => $batchId,
                'search' => $search,
            ],
        ]);
    }
}
