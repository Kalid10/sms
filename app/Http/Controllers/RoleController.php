<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\AssignRoleRequest;
use App\Http\Requests\Roles\RemoveRoleRequest;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

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
}
