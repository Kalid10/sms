<?php

namespace App\Http\Controllers;

use App\Models\Absentee;
use App\Models\BatchSession;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsenteesController extends Controller
{
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'batch_session_id' => 'required|integer|exists:batch_sessions,id',
            'user_type' => 'required|in:'.User::TYPE_STUDENT.','.User::TYPE_TEACHER,
            'absentees' => 'required|array|min:1',
            'absentees.*.user_id' => 'required|integer|exists:users,id|distinct:strict',
            'absentees.*.reason' => 'nullable|string',
        ]);

        // Get the batch session
        $batchSession = BatchSession::find($request->batch_session_id);

        // Check if the batch session is already closed
        if ($batchSession->status !== BatchSession::STATUS_IN_PROGRESS) {
            return redirect()->back()->with('error', 'Class is not active.');
        }

        // Check if the student is enrolled in the batch
        $batchId = $batchSession->batchSchedule->batchSubject->batch->id;
        $absenteeUserIds = array_column($request->absentees, 'user_id');
        $absenteeUsers = User::with('student')->whereIn('id', $absenteeUserIds)->get();

        foreach ($absenteeUsers as $user) {
            if ($request->user_type === User::TYPE_STUDENT && $user->type !== User::TYPE_STUDENT) {
                return redirect()->back()->with('error', $user->name.' is not a student.');
            }

            if ($request->user_type === User::TYPE_STUDENT && ! $user->student->batches()->where('batch_id', $batchId)->first()) {
                return redirect()->back()->with('error', $user->name.' is not enrolled in this class.');
            }
        }

        try {
            DB::beginTransaction();

            // Make the absentees array collection
            $absentees = collect($request->absentees);

            // Update existing records with new reasons or insert new records
            foreach ($absentees as $absentee) {
                Absentee::updateOrInsert(
                    [
                        'batch_session_id' => $request->batch_session_id,
                        'user_id' => $absentee['user_id'],
                    ],
                    [
                        'reason' => $absentee['reason'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Fetch existing absent records for the specified batch
            $existingAbsentRecords = Absentee::where('batch_session_id', $request->batch_session_id)->pluck('user_id');

            // Find students to be removed from the absent list
            $usersToRemove = $existingAbsentRecords->diff($absentees->pluck('user_id'));

            // Remove students from the absent list
            Absentee::where('batch_session_id', $request->batch_session_id)->whereIn('user_id', $usersToRemove)->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');
        }

        return redirect()->back()->with('success', 'Absentees updated successfully.');
    }
}
