<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Admin;
use App\Models\Level;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        // Get search key
        $searchKey = $request->input('search');

        // Get page numbers
        $userPage = $request->input('user_page', 1);
        $logPage = (int) $request->input('log_page', 1);

        // Get users
        $users = User::select('id', 'name', 'email', 'type', 'gender')
            ->where('name', 'like', '%'.$searchKey.'%')
            ->paginate(12, ['*'], 'user_page', $userPage);

        // Get activity logs of users
        $activityLog = Activity::where('log_name', 'user')
            ->with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'log_page', $logPage);

        // Get current batch students count
        $studentsCount = Student::with('currentBatch')->count();

        // Get active school year teachers count through batch subject and batch and school year
        $teachersCount = Teacher::whereHas('batchSubjects', function ($query) {
            $query->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            });
        })->count();

        // Get active school year admins
        $adminsCount = Admin::with('schoolYear', SchoolYear::getActiveSchoolYear())->count();

        // Get new users registered the last 30 days
        $newUsersCount = User::where('created_at', '>=', now()->subDays(30))->count();

        Log::info($newUsersCount);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'activity_log' => $activityLog,
            'students_count' => $studentsCount,
            'teachers_count' => $teachersCount,
            'admins_count' => $adminsCount,
            'new_users_count' => $newUsersCount,
        ]);
    }

    public function profile(): Response
    {
        return Inertia::render('Admin/Users/Profile');
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $user = User::find($request->id);

        $user->update($request->validated());

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        // Check if this is the logged-in user
        if ($request->id != auth()->user()->id) {
            return redirect()->back()->with('error', 'You are not authorized to update this user.');
        }
        // Check if old password and current password are same
        if (! Hash::check($request->get('current_password'), auth()->user()->password)) {
            return back()->withErrors(['password' => 'Current Password is Invalid']);
        }
        // Check if current password and new password are same
        if ($request->get('current_password') == $request->get('password')) {
            return back()->withErrors(['password' => 'New Password cannot be same as current password']);
        }

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated Successfully');
    }

    public function student(): Response
    {
        $levels = Level::all();

        return Inertia::render('Admin/Users/Create/Student', [
            'levels' => $levels,
        ]);
    }

    public function admin(): Response
    {
        return Inertia::render('Admin/Users/Create/Admin');
    }

    public function teacher(): Response
    {
        return Inertia::render('Admin/Users/Create/Teacher');
    }
}
