<?php

namespace App\Http\Controllers\Web;

use App\Models\Absentee;
use App\Models\Announcement;
use App\Models\Flag;
use App\Models\Level;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function show(Request $request): Response
    {
        $searchKey = $request->input('search');

        // Get teachers, students, admins and subjects count
        $teachersCount = User::where('type', 'teacher')->count();
        $studentsCount = User::where('type', 'student')->count();
        $subjectsCount = Subject::all()->count();
        $adminsCount = User::where('type', 'admin')->count();

        $levels = Level::with([
            'levelCategory',
            'batches' => function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            },
        ])->get();

        $activeStudents = User::with('student.batches.activeBatch')->where('type', 'student')->count();

        // Get absentee records of active batch students
        $absenteeRecords = Absentee::with('user.student.batches.activeBatch')->count();

        // Get admins of active batch
        $admins = User::with('roles')->where('type', 'admin')->get();

        $schoolYear = SchoolYear::getActiveSchoolYear();

        $announcements = Announcement::where('school_year_id', $schoolYear?->id)->with('author.user')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })->orderBy('updated_at', 'DESC')->get()->take(5);

        $schoolScheduleDate = $request->input('school_schedule_date') ?? now()->addDays(4);
        $schoolSchedule = SchoolSchedule::where('school_year_id', $schoolYear?->id)
            ->whereDate('start_date', '<=', Carbon::parse($schoolScheduleDate))
            ->whereDate('end_date', '>=', Carbon::parse($schoolScheduleDate))
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        $flags = Flag::with('flaggedBy', 'flaggable.user.admin', 'batchSubject.subject')->latest('updated_at')->paginate(7);

        return Inertia::render('Admin/Index', [
            'teachers_count' => $teachersCount,
            'students_count' => $studentsCount,
            'subjects_count' => $subjectsCount,
            'admins_count' => $adminsCount,
            'levels' => $levels,
            'active_students' => $activeStudents,
            'absentee_records' => $absenteeRecords,
            'admins' => $admins,
            'school_year' => $schoolYear,
            'announcements' => $announcements,
            'school_schedule' => $schoolSchedule,
            //            'students' => StudentService::getAllStudents($request),
            'students' => Inertia::lazy(fn () => User::with('student.currentBatch')->where('type', 'student')
                ->when($searchKey, function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                })
                ->orderBy('name', 'asc')
                ->get()
            ),
            'flags' => $flags,
        ]);
    }

    public function schedule(Request $request): Response
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'search' => 'nullable|string',
        ]);
        $searchKey = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()?->id)
            ->when($startDate, function ($query) use ($startDate) {
                return $query->whereDate('start_date', '>=', Carbon::parse($startDate));
            })
            ->when($endDate, function ($query) use ($endDate) {
                return $query->whereDate('end_date', '<=', Carbon::parse($endDate));
            })
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })
            ->orderBy('start_date', 'asc')
            ->paginate(7)->appends(request()->query());

        return Inertia::render('Admin/Schedules/Index', [
            'school_schedule' => $schoolSchedule,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $searchKey,
            ],
        ]);
    }

    public function announcements(Request $request): Response
    {
        $searchKey = $request->input('search');

        $schoolYear = SchoolYear::getActiveSchoolYear();

        $announcements = Announcement::where('school_year_id', $schoolYear?->id)->with('author.user')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->where('title', 'like', "%{$searchKey}%");
            })->paginate(10);

        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements,
        ]);
    }
}
