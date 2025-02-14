<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AbsenteeHelper;
use App\Models\Absentee;
use App\Models\BatchSession;
use App\Models\StaffAbsentee;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
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
            'absentees' => 'nullable|array|min:0',
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

        // Check if the teacher is assigned to the batchSubject
        $batchSubject = $batchSession->load('batchSchedule.batchSubject.teacher.user')->batchSchedule->batchSubject;

        if (auth()->user()->id !== $batchSubject->teacher->user->id) {
            return redirect()->back()->with('error', 'You are not assigned to this class.');
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

            foreach ($usersToRemove as $userId) {
                $absentee = Absentee::where('user_id', $userId)->whereDate('created_at', Carbon::today())->latest()->first();

                if ($absentee) {
                    $absentee->update([
                        'next_class_attended_flag' => true,
                    ]);
                }
            }

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
            'reason' => 'required|string',
            'type' => 'required|string',
            'is_leave' => 'required|boolean',
        ]);

        return DB::transaction(function () use ($request) {

            $user = User::with(['teacher.batchSessions.batchSchedule'])
                ->findOrFail($request->user_id);

            if ($request->batch_session_id) {
                $batchSession = BatchSession::with('batchSchedule')->findOrFail($request->batch_session_id);
                $this->verifyBatchSession($batchSession, $user, $request->type);
            }

            if (StaffAbsentee::where('user_id', $user->id)->whereDate('created_at', Carbon::today())->first()) {
                return redirect()->back()->with('error', 'Staff is already absent.');
            }

            StaffAbsentee::create($request->only('batch_session_id', 'user_id', 'reason', 'type', 'is_leave'));

            if ($request->type === User::TYPE_TEACHER && $request->is_leave) {
                $this->updateTeacherLeaveInfo($user);
            }

            return redirect()->back()->with('success', 'Staff Absentees updated successfully.');

        }, 3); // Three is the number of attempts to run the block of code within the closure
    }

    private function verifyBatchSession($batchSession, $user, $type)
    {
        // Check if the batch session is in progress
        if ($batchSession->status !== BatchSession::STATUS_IN_PROGRESS) {
            return redirect()->back()->with('error', 'Class is not active.');
        }

        // Check if the user is a teacher when the request type is for a teacher
        if ($type === User::TYPE_TEACHER && $user->type !== User::TYPE_TEACHER) {
            return redirect()->back()->with('error', $user->name.' is not a teacher.');
        }

        // Check if the teacher is assigned to this batch
        $batchId = $batchSession->batchSchedule->batch_id;
        $isTeacherAssigned = $user->teacher->batchSessions->contains(function ($batchSession) use ($batchId) {
            return $batchSession->batchSchedule->batch_id == $batchId;
        });

        if (! $isTeacherAssigned) {
            return redirect()->back()->with('error', $user->name.'is not assigned to this class.');
        }
    }

    private function updateTeacherLeaveInfo($user): void
    {
        try {
            // Decode the 'leave_info' and subtract 1 from 'remaining'.
            $leaveInfo = $user->teacher->leave_info;
            if (isset($leaveInfo['remaining'])) {
                $leaveInfo['remaining']--;
                $user->teacher->update(['leave_info' => $leaveInfo]);
            } else {
                // Log an error or redirect back if 'remaining' is not set
                Log::error("The 'remaining' field is not set in 'leave_info'");
                redirect()->back()->with('error', 'Something went wrong.');
            }
        } catch (Exception $e) {
            // Handle the exception
            Log::error($e->getMessage());
            redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function index(Request $request): Response
    {
        $searchKey = request()->query('search');

        $queryKey = request()->query('find');

        $studentsQueryKey = request()->query('students_find');

        $staff = User::whereIn('type', [User::TYPE_TEACHER, User::TYPE_ADMIN])
            ->when($searchKey, function ($query, $searchKey) {
                $query->where('name', 'like', '%'.$searchKey.'%');
            })->get()->take(5);

        $studentAbsentees = Absentee::with('user')
            ->when($studentsQueryKey, function ($query, $studentsQueryKey) {
                $query->whereHas('user', function ($query) use ($studentsQueryKey) {
                    $query->where('name', 'like', '%'.$studentsQueryKey.'%');
                });
            })->paginate(10);

        $userType = $request->input('type');

        $date = $request->input('date');

        // Get staff absentees of the day
        $staffAbsenteesOfTheDay = StaffAbsentee::with('user')
            ->whereDate('created_at', Carbon::today())
            ->when($queryKey, function ($query, $queryKey) {
                $query->whereHas('user', function ($query) use ($queryKey) {
                    $query->where('name', 'like', '%'.$queryKey.'%');
                });
            })
            ->when($userType, function ($query, $userType) {
                $query->whereHas('user', function ($query) use ($userType) {
                    if ($userType === 'all') {
                        $query->whereIn('type', [User::TYPE_TEACHER, User::TYPE_ADMIN]);
                    } else {
                        $query->where('type', $userType);
                    }
                });
            })
            ->when($date, function ($query, $date) {
                $query->whereDate('created_at', $date);
            })
            ->paginate(10);

        $userTypes = $userType === ['all', 'admin', 'teacher'];

        return Inertia::render('Admin/Absentees/Index', [
            'staff' => Inertia::lazy(fn () => $staff),
            'student_absentees' => $studentAbsentees,
            'staff_absentees_of_the_day' => $staffAbsenteesOfTheDay,
            'user_types' => $userTypes,
            'filters' => [
                'user_type' => $userType,
                'date' => $date,
            ],
        ]);
    }

    public function updateStaffAbsentee(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|integer|exists:staff_absentees,id',
            'reason' => 'required|string',
            'is_leave' => 'required|boolean',
        ]);

        $staffAbsentee = StaffAbsentee::find($request->id);

        $staffAbsentee->update([
            'reason' => $request->reason,
            'is_leave' => $request->is_leave,
        ]);

        return redirect()->back()->with('success', 'Staff Absentee updated successfully.');
    }
}
