<?php

namespace App\Http\Controllers\Web;

use App\Jobs\SendAnnouncementNotification;
use App\Models\Announcement;
use App\Models\SchoolYear;
use App\Services\TeacherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function create(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'expires_on' => 'required|date',
            'target_group' => 'required|array|min:1',
            'target_group.*' => 'string|in:all,teachers,guardians,admins',
            'target_batches' => 'nullable|array',
            'target_batches.*' => 'integer|exists:batches,id',
        ]);

        // Get the current authenticated user
        $user = auth()->user()->id;

        // Get the current school year
        $schoolYear = SchoolYear::getActiveSchoolYear()->id;

        if (! $schoolYear) {
            return redirect()->back()->withErrors(['error', 'No active school year found']);
        }

        // Create announcement of the current school year with the current authenticated user
        $announcement = new Announcement($validated);
        $announcement->author()->associate($user);
        $announcement->schoolYear()->associate($schoolYear);
        $announcement->save();

        SendAnnouncementNotification::dispatch($announcement);

        return redirect()->back()->with('success', 'Announcement created successfully');
    }

    public function update(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'string|max:255',
            'body' => 'string',
            'expires_on' => 'date',
            'id' => 'required|integer|exists:announcements,id',
            'target_group' => 'required|array|min:1',
            'target_group.*' => 'string|in:all,teachers,guardians,admins',
            'target_batches' => 'nullable|array',
            'target_batches.*' => 'integer|exists:batches,id',
        ]);

        // TODO:: Check if the logged-in user is the author of the announcement or is able to edit announcements
        // Find the announcement
        $announcement = Announcement::find($request->id);

        // Check if the announcements school year is the current school year
        if ($announcement->schoolYear->end_date !== null) {
            return redirect()->back()->with('error', 'Announcement is not in the current school year');
        }

        // Update the announcement
        $announcement->update($validated);

        return redirect()->back()->with('success', 'Announcement updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        // Find the announcement
        $announcement = Announcement::findOrFail($id);

        // Delete the announcement
        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement deleted successfully');
    }
}
