<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Admin;
use App\Models\Batch;
use App\Models\Guardian;
use App\Models\Level;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $users = User::select('id', 'name', 'email', 'type', 'gender', 'is_blocked')
            ->where('name', 'like', '%'.$searchKey.'%')
            ->with([
                'student:id,user_id',
                'teacher:id,user_id',
                'admin:id,position,user_id',
                'guardian:id,user_id',
                'guardian.children.batches.batch.level',
                'guardian.children.grades',
                'guardian.children.user:id,name,username,gender,profile_image',
                'address',
                'roles',
            ])
            ->paginate(12, ['*'], 'user_page', $userPage);

        // Get activity logs of users
        $activityLog = Activity::where('log_name', 'user')
            ->with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'log_page', $logPage);

        // Get current batch students count
        $studentsCount = Student::with('currentBatch')->count();

        // Get teachers count with the current school year
        $teachersCount = Teacher::with('user.is_blocked', 1)->count();

        // Get active school year admins
        $adminsCount = Admin::with('schoolYear', SchoolYear::getActiveSchoolYear())->count();

        // Get new users registered the last 30 days
        $newUsersCount = User::where('created_at', '>=', now()->subDays(30))->count();

        // Get active school year guardians
        $guardiansCount = Guardian::with('schoolYear', SchoolYear::getActiveSchoolYear())->count();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'activity_log' => $activityLog,
            'students_count' => $studentsCount,
            'teachers_count' => $teachersCount,
            'admins_count' => $adminsCount,
            'new_users_count' => $newUsersCount,
            'guardians_count' => $guardiansCount,
        ]);
    }

    public function profile(): Response
    {
        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Profile',
            User::TYPE_ADMIN => 'Admin/Profile',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page);
    }

    public function update(UpdateRequest $request): RedirectResponse
    {
        $user = User::find($request->id);

        $user->update($request->validated());

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->refresh();
        // Check if this is the logged-in user
        if ($request->id != $user->id) {
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
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated Successfully');
    }

    public function student(Request $request): Response
    {
        $levels = Level::whereHas('batches', function ($query) {
            $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
        })->with('batches:id,level_id,section,min_students,max_students')
            ->with('batches.students.student.user:id,name')->get();

        $searchKey = $request->input('search');

        return Inertia::render('Admin/Users/Create/Student', [
            'levels' => $levels,
            'guardians' => Inertia::lazy(fn () => Guardian::with([
                'user:id,name',
            ])->select('id', 'user_id')
                ->when($searchKey, function ($query) use ($searchKey) {
                    return $query->whereHas('user', function ($query) use ($searchKey) {
                        return $query->where('name', 'like', "%{$searchKey}%");
                    });
                })
                ->orderBy('user_id', 'asc')->get()
                ->take(5)
            ),
        ]);
    }

    public function admin(): Response
    {
        return Inertia::render('Admin/Users/Create/Admin');
    }

    public function teacher(): Response
    {
        $batches = Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->with('level')
            ->with('subjects.subject')
            ->with('teachers.user')
            ->get();

        return Inertia::render('Admin/Users/Create/Teacher', [
            'batches' => $batches,
        ]);
    }

    public function uploadImage(Request $request): RedirectResponse
    // Get the logged in user
    {
        $user = auth()->user();
        // This refresh code is used to unload all relations of the user,
        // but the main use of this code is to refresh the user model
        $user->refresh();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get file from request
        $file = $request->file('image');

        // Resize the image before uploading to Spaces
        $img = ImageService::resize($file->getRealPath(), 300, 200);

        // Generate a new filename for the resized image
        $filename = $user->name.'.'.$file->getClientOriginalExtension();

        // If the user already has an image, delete it from Spaces
        if ($user->profile_image) {
            $imageName = substr($user->profile_image, strrpos($user->profile_image, '/') + 1);

            // Delete old image
            if (! Storage::disk('spaces')->delete($imageName)) {
                throw new Exception("Error deleting file: {$imageName}");
            }
        }

        // Upload the resized image to Spaces
        ImageService::upload($img, $filename);

        $user->update([
            'profile_image' => Storage::disk('spaces')->url('rigel/profile-images/'.$filename),
        ]);

        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

    public function blockUnblock(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Retrieve the user
        $user = User::find($request->user_id);

        // Toggle the is_blocked attribute
        $user->update([
            'is_blocked' => ! $user->is_blocked,
        ]);

        // Refresh the model to get the updated 'is_blocked' value
        $user->refresh();

        // Create a success message based on the new is_blocked value
        $message = $user->is_blocked ? 'User blocked successfully.' : 'User unblocked successfully.';

        return redirect()->back()->with('success', $message);
    }
}
