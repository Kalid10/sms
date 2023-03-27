<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\AssignRequest;
use App\Http\Requests\Roles\RemoveRequest;
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
    public function gettingStarted(): Response
    {
        return Inertia::render('GettingStarted/Index');
    }

    public function assign(AssignRequest $request): RedirectResponse
    {
        try {
            // Get validated data
            $validatedData = $request->validated();

            // Get user
            $user = User::find($validatedData['user_id']);

            // Sync roles
            $user->roles()->sync($validatedData['roles']);

            return redirect()->back()->with('success', 'Role assigned successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function remove(RemoveRequest $request): RedirectResponse
    {
        // Get validated data
        $validated = $request->validated();

        try {
            // Delete user roles
            foreach ($validated['roles'] as $role) {
                $userRole = UserRole::where('role_name', $role)
                    ->where('user_id', $validated['user_id'])
                    ->first();

                $userRole->delete();
                Log::error($userRole);

                Log::error('Role: '.$role.' User: '.$validated['user_id']);

                Log::error($userRole);
            }

            return redirect()->back()->with('success', 'Roles deleted successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
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
        return Inertia::render('Roles/Index', [
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

        return Inertia::render('Roles/Detail', [
            'activities' => $activities,
        ]);
    }

    public function list(): Response
    {
        // Get all roles
        $roles = Role::all();

        return Inertia::render('Roles/Detail', [
            'roles' => $roles]);
//         return redirect()->back()->with('roles', $roles);
    }

    // Add function to get user roles
    public function details(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        try {
            $user = User::find($request->user_id);

            $activities = Activity::where('log_name', 'user_role')
                ->where('properties->attributes->user_id', $request->user_id)
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

            // TODO: Change this route to the correct view
            return Inertia::render('Roles/Detail', [
                'user_roles' => $user->roles,
                'activities' => $activities,
                'roles' => Role::all(),
            ]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
