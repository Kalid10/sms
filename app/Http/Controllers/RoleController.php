<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\AssignRoleRequest;
use App\Http\Requests\Roles\RemoveRoleRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class RoleController extends Controller
{
    public function assign(AssignRoleRequest $request): RedirectResponse
    {
        try {
            // Get validated data
            $validatedData = $request->validated();

            // Create or restore user roles
            foreach ($validatedData['roles'] as $role) {
                UserRole::withTrashed()
                    ->where('role_name', $role)
                    ->where('user_id', $validatedData['user_id'])
                    ->firstOrCreate([
                        'role_name' => $role,
                        'user_id' => $validatedData['user_id'],
                    ]);
            }

            return redirect()->back()->with('success', 'Role assigned successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function remove(RemoveRoleRequest $request): RedirectResponse
    {
        // Get validated data
        $validated = $request->validated();

        try {
            // Delete user roles
            foreach ($validated['roles'] as $role) {
                UserRole::where('role_name', $role)
                    ->where('user_id', $validated['user_id'])
                    ->delete();
            }

            return redirect()->back()->with('success', $validated['role_name'].' deleted successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function users(Request $request): Response
    {
        // Get search query
        $search = $request->get('search');

        // Get users filtered by name or email and,
        // feel free to increase the pagination
        $users = User::with('roles')
            ->where('name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->paginate(10);

        // TODO: Change this route to the correct view
        return Inertia::render('Welcome', [
            'users' => $users,
        ]);
    }

    // Function to get all role activities from activity log from the spatie activity log package
    public function activities(): Response
    {
        // Get user role activities from activity log with log name,description, properties,causer,created at and get the user from user_id on the properties
        $activities = Activity::where('log_name', 'user_role')
            ->with('causer')
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'causer' => $activity->causer,
                    'created_at' => $activity->created_at,
                    'user' => User::where('id', $activity->properties['attributes']['user_id'])->first(),
                    'role' => $activity->properties['attributes']['role_name'],
                ];
            });

        return Inertia::render('Welcome', [
            'activities' => $activities,
        ]);
    }

    public function list(): Response
    {
        // Get all roles
        $roles = Role::all();

        // TODO: Change this route to the correct view
        return Inertia::render('Welcome', [
            'roles' => $roles,
        ]);
    }

    // Add function to get user roles
    public function userRoles(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        try {
            $user = User::find($request->user_id);

            // TODO: Change this route to the correct view
            return Inertia::render('Welcome', [
                'user_roles' => $user->roles,
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
