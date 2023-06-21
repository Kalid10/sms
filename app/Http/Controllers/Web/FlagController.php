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
//        if (!$request->batch_subject_id) {
//            if (auth()->user()->type === User::TYPE_TEACHER) {
//                $studentActiveBatch = Student::find($request->flaggable_id)->activeBatch();
//                Log::info($studentActiveBatch);
//                $homeroomClasses = auth()->user()->teacher->homeroom;
//
//            }
//        }

        $flagExists = Flag::where('flaggable_id', $request->flaggable_id)
            ->where('flaggable_type', Student::class)
            ->where('type', $request->flag_type)
            ->where('flagged_by', auth()->user()->id)
            ->where('batch_subject_id', $request->batch_subject_id)
            ->first();

        if ($flagExists) {
            return redirect()->back()->with('error', 'You have already flagged this student!');
        }

        Flag::create([
            'flaggable_id' => $request->flaggable_id,
            'flaggable_type' => Student::class,
            'type' => $request->flag_type,
            'description' => $request->description,
            'flagged_by' => auth()->user()->id,
            'batch_subject_id' => $request->batch_subject_id,
            'expires_at' => date('Y-m-d H:i:s', strtotime($request->expires_at)),
            'quarter_id' => Quarter::getActiveQuarter()->id,
        ]);

        return redirect()->back()->with('success', 'You have successfully flagged '.$student->user->name);
    }
}
