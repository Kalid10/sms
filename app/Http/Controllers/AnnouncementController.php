<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Get all announcements
        $announcements = Announcement::all();

        // TODO: change this to a view when the view is ready
        return inertia('Welcome', [
            'announcements' => $announcements,
        ]);
    }

    public function create(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'expires_on' => 'required|date',
            'target_group' => 'required|array|min:1|in:all,students,teachers,guardians,admins',
        ]);

        // Create the announcement
        Announcement::create(array_merge($validated, [
            'author_id' => auth()->user()->id,
        ]));

        return back()->with('success', 'Announcement created successfully');
    }

    public function update(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'string|max:255',
            'body' => 'string',
            'expires_on' => 'date',
            'id' => 'required|integer|exists:announcements,id',
            'target_group' => 'required|array|min:1|in:all,students,teachers,guardians,admins',
        ]);

        // TODO:: Check if the logged in user is the author of the announcement or is able to edit announcements

        // Find the announcement
        $announcement = Announcement::find($request->id);

        // Update the announcement
        $announcement->update($validated);

        return back()->with('success', 'Announcement updated successfully');
    }

    public function destroy($id)
    {
        // Find the announcement
        $announcement = Announcement::findOrFail($id);

        // Delete the announcement
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully');
    }
}
