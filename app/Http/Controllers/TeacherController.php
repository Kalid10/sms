<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teachers\AssignHomeroomRequest;
use App\Models\HomeroomTeacher;
use Illuminate\Http\RedirectResponse;

class TeacherController extends Controller
{
    public function assignHomeroomTeacher(AssignHomeroomRequest $request): RedirectResponse
    {
        HomeroomTeacher::create([
            'teacher_id' => $request->teacher_id,
            'batch_id' => $request->batch_id,
        ]);

        return redirect()->back()->with('success', 'Homeroom teacher assigned successfully.');
    }

    public function removeHomeroomTeacher($id): RedirectResponse
    {
        // Find the homeroom teacher by ID or fail
        $homeroomTeacher = HomeroomTeacher::findOrFail($id);

        // Delete the homeroom teacher
        $homeroomTeacher->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Homeroom teacher removed successfully.');
    }
}
