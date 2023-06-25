<?php

namespace App\Http\Controllers\Web;

use App\Helpers\StudentHelper;
use App\Models\Flag;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FlagController extends Controller
{
    public function flagStudent(Request $request)
    {
        $request->validate([
            'flaggable_id' => 'required|exists:students,id',
            'flag_type' => 'required',
            'description' => 'required|string',
            'batch_subject_id' => 'nullable|exists:batch_subjects,id',
            'expires_at' => 'required|date',
        ]);

        $student = Student::find($request->flaggable_id)->first()->load('user');

        // TODO: If is_homeroom is set to true, check if the teacher is the homeroom of the flaggable student

        StudentHelper::flagStudent(
            $request->flaggable_id,
            $request->flag_type,
            $request->description,
            auth()->user()->id,
            $request->batch_subject_id,
            $request->expires_at,
            $request->is_homeroom
        );

        return redirect()->back()->with('success', 'You have successfully flagged '.$student->user->name);
    }

    public function delete($id): RedirectResponse
    {
        $flag = Flag::find($id);

        $flag->delete();

        return redirect()->back()->with('success', 'Flag deleted successfully');
    }
}
