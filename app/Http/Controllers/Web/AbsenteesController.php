<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AbsenteeHelper;
use App\Models\Absentee;
use App\Models\BatchSession;
use App\Models\StaffAbsentee;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

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
        $batchId = $batchSession->batchSchedule->batch_id;
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

            AbsenteeHelper::computeAbsenteeData($absentees, $batchSession);

            // Map data and recalculate for the removed users
            $usersToRemove = $existingAbsentRecords->diff($absentees->pluck('user_id'))
                ->map(function ($userId) {
                    return ['user_id' => $userId];
                });

            AbsenteeHelper::computeAbsenteeData($usersToRemove, $batchSession);
        } catch (Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');
        }

        return redirect()->back()->with('success', 'Absentees updated successfully.');
    }

    public function getStudentAbsenteePercentage(Request $request): Response
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'school_year_id' => 'integer|exists:school_years,id',
        ]);

        return Inertia::render('Welcome', [
            'absenteePercentage' => Student::find($request->student_id)->absenteePercentage(),
        ]);
    }

    public function createStaffAbsentee(Request $request): RedirectResponse
    {
        $request->validate([
            'batch_session_id' => 'nullable|exists:batch_sessions,id',
            'user_id' => 'required|integer|exists:users,id',
            'reason' => 'nullable|string',
            'type' => 'required|string',
        ]);

        // check if request has batch session id
        if ($request->batch_session_id) {
            // Get the batch session
            $batchSession = BatchSession::find($request->batch_session_id);

            $batchId = $batchSession->batchSchedule->batch_id;

            // Check if the batch session is already closed
            if ($batchSession->status !== BatchSession::STATUS_IN_PROGRESS) {
                return redirect()->back()->with('error', 'Class is not active.');
            }

            $user = User::with('teacher.batchSessions.batchSchedule')->where('id', $request->user_id)->first();

            if ($request->type === User::TYPE_TEACHER && $user->type !== User::TYPE_TEACHER) {
                return redirect()->back()->with('error', $user->name.' is not a teacher.');
            }

            // Check if the teacher is assigned to the batch
            if ($request->type === User::TYPE_TEACHER &&
                ! $user->teacher->batchSessions->contains(function ($batchSession) use ($batchId) {
                    return $batchSession->batchSchedule->batch_id == $batchId;
                })
            ) {
                return redirect()->back()->with('error', $user->name.' is not assigned to this class.');
            }
        }

        StaffAbsentee::updateOrInsert(
            [
                'batch_session_id' => $request->batch_session_id,
                'user_id' => $request->user_id,
            ],
            [
                'reason' => $request->reason,
                'type' => $request->type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Staff Absentees updated successfully.');
    }

    public function staff(): Response
    {
        $searchKey = request()->query('search');

        $queryKey = request()->query('find');

        $staff = User::whereIn('type', [User::TYPE_TEACHER, User::TYPE_ADMIN])
            ->when($searchKey, function ($query, $searchKey) {
                $query->where('name', 'like', '%'.$searchKey.'%');
            })->get();

        $staff_absentees = StaffAbsentee::with('user')
            ->when($queryKey, function ($query, $queryKey) {
                $query->whereHas('user', function ($query) use ($queryKey) {
                    $query->where('name', 'like', '%'.$queryKey.'%');
                });
            })->paginate(10);

        return Inertia::render('Admin/Absentees/StaffAbsentees', [
            'staff_absentees' => $staff_absentees,
            'staff' => $staff,
        ]);
    }
}
