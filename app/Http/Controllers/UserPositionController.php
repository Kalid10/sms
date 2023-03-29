<?php

namespace App\Http\Controllers;

use App\Models\UserPosition;
use Illuminate\Http\Request;

class UserPositionController extends Controller
{
    public function index(Request $request)
    {
        // Get search key
        $searchKey = $request->input('search');

        // Get user positions
        $usersPositions = UserPosition::select('id', 'name', 'description', 'role_names')->where('name', 'like', '%'.$searchKey.'%')->paginate(10);

        // To do: Change the path when position index page is created
        return inertia('Welcome', [
            'userPositions' => $usersPositions,
        ]);
    }

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

    public function update(Request $request, UserPosition $userPosition)
    {
        // Validate request name and role_ids
        $request->validate([
            'name' => 'required|string|unique:user_positions,name,'.$userPosition->id,
            'description' => 'nullable|string',
            'role_names' => 'required|array',
            'role_names.*' => 'required|string|exists:roles,name',
        ]);

        // Update user position
        $userPosition->update([
            'name' => $request->name,
            'role_names' => json_encode($request->role_names),
            'description' => $request->description,
        ]);

        // Return response
        return redirect()->back()->with('success', $userPosition->name.' updated successfully');
    }

    public function destroy(UserPosition $userPosition)
    {
        // Delete user position
        $userPosition->delete();

        // Return response
        return redirect()->back()->with('success', $userPosition->name.' deleted successfully');
    }
}
