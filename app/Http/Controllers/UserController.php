<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        // Get search key
        $searchKey = $request->input('search');

        // For paginated data, get perPage value
        $perPage = $request->input('per_page', 10);

        // Get users
        $users = User::select('id', 'name', 'email', 'type', 'gender')->where('name', 'like', '%'.$searchKey.'%')->paginate($perPage);

        // Get activity logs of users
        $activityLog = Activity::where('log_name', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'activity_log' => $activityLog,
        ]);
    }

    public function profile(): Response
    {
        return Inertia::render('Users/Profile');
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

    public function student()
    {
        $levels = Level::all();

        return Inertia::render('Users/Create/Student', [
            'levels' => $levels,
        ]);
    }

    public function admin()
    {
        return Inertia::render('Users/Create/Admin');
    }

    public function teacher()
    {
        return Inertia::render('Users/Create/Teacher');
    }
}
