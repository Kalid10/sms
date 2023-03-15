<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRoleRequest;
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
            redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
