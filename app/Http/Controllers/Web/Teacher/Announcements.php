<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Http\Controllers\Web\Controller;
use App\Models\Announcement;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Announcements extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'teacher_id' => 'nullable|integer|exists:teachers,id',
            'search' => 'nullable|string|max:255',
        ]);
        $searchKey = $request->input('search');

        $teacher = auth()->user()->isTeacher() ? auth()->user()->teacher : Teacher::findOrFail($request->input('teacher_id'))->load('user');

        if (! $teacher) {
            abort(403);
        }

        $announcements = Announcement::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->
        whereJsonContains('target_group', 'all')
            ->orWhereJsonContains('target_group', 'teachers')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })
            ->orderBy('created_at', 'desc')
            ->with('author.user:id,name')
            ->paginate(10);

        // Get teacher's feedbacks using teacher service getTeacherFeedbacks function
        $feedbacks = TeacherService::getTeacherFeedbacks($teacher);

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Announcement/Index',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'announcements' => $announcements,
            'feedbacks' => $feedbacks,
            'teacher' => $teacher,
        ]);
    }
}
