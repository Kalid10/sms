<?php

namespace App\Http\Controllers\Web;

use App\Models\Flag;
use App\Models\Quarter;
use App\Models\Student;
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

        // TODO: If is_homeroom is set to true,check if the teacher is the homeroom of the flaggable student
        $flag = Flag::where('flaggable_id', $request->flaggable_id)
            ->where('flaggable_type', Student::class)
            ->where('flagged_by', auth()->user()->id)
            ->where('batch_subject_id', $request->batch_subject_id)
            ->first();

        $type = $request->flag_type;

        // If a flag exists, update the type and description
        if ($flag) {
            $existingTypes = $flag->type;
            // If the new type does not exist in the array, add it
            if (! in_array($type, $existingTypes)) {
                $existingTypes[] = $type;
            }

            // Update the flag
            $flag->update([
                'type' => $existingTypes,
                'description' => $request->description,
                'expires_at' => date('Y-m-d H:i:s', strtotime($request->expires_at)),
                'quarter_id' => Quarter::getActiveQuarter()->id,
            ]);
        } else {
            // If the flag doesn't exist, create a new one
            Flag::create([
                'flaggable_id' => $request->flaggable_id,
                'flaggable_type' => Student::class,
                'type' => (array) $type,
                'description' => $request->description,
                'flagged_by' => auth()->user()->id,
                'batch_subject_id' => $request->batch_subject_id,
                'expires_at' => date('Y-m-d H:i:s', strtotime($request->expires_at)),
                'quarter_id' => Quarter::getActiveQuarter()->id,
                'is_homeroom' => $request->is_homeroom,
            ]);
        }

        return redirect()->back()->with('success', 'You have successfully flagged '.$student->user->name);
    }
}
