<?php

namespace App\Http\Controllers;

use App\Http\Requests\Roles\AssignRoleRequest;
use App\Http\Requests\Roles\RemoveRoleRequest;
use App\Models\UserRole;
use Exception;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function assignRole(AssignRoleRequest $request)
    {
        try {
            // Get validated data
            $validatedData = $request->validated();

            // Create or restore user role
            UserRole::withTrashed()
                ->where('role_name', $validatedData['role_name'])
                ->where('user_id', $validatedData['user_id'])
                ->firstOrCreate($validatedData);

            return redirect()->back()->with('success', 'Role assigned successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function removeRole(RemoveRoleRequest $request)
    {
        // Get validated data
        $validated = $request->validated();

        try {
            // Delete user role
            UserRole::where('role_name', $validated['role_name'])
                ->where('user_id', $validated['user_id'])
                ->delete();

            return redirect()->back()->with('success', $validated['role_name'].' deleted successfully.');
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return  redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
