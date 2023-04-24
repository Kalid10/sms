<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index()
    {
    }

    public function show(Student $student): Response
    {
        $student = $student->load('user', 'guardian', 'guardian.user', 'batches', 'batches.batch', 'batches.batch.level');

        $activeBatch = $student->batches()->whereHas('batch.schoolYear', function ($query) {
            $query->where('end_date', null);
        })->first();

        return Inertia::render('Students/Single', [
            'student' => $student,
            'active_batch' => $activeBatch,
        ]);
    }
}
