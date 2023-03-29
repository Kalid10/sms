<?php

namespace App\Http\Controllers;

use App\Models\UserPosition;
use Illuminate\Http\Request;

class UserPositionController extends Controller
{
    public function create(Request $request)
    {
        // Validate request name and role_ids
        $request->validate([
            'name' => 'required|string|unique:user_positions,name',
            'description' => 'nullable|string',
            'role_names' => 'required|array',
            'role_names.*' => 'required|string|exists:roles,name',
        ]);

        // Create user position
        $userPosition = UserPosition::create([
            'name' => $request->name,
            'role_names' => json_encode($request->role_names),
            'description' => $request->description,
        ]);

        // Return response
        return redirect()->back()->with('success', $userPosition->name.' added successfully');
    }
}
