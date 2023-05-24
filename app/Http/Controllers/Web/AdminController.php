<?php

namespace App\Http\Controllers\Web;

use App\Models\Absentee;
use App\Models\Level;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    public function index(): Response
    {
        // Get teachers, students, admins and subjects count
        $teachersCount = User::where('type', 'teacher')->count();
        $studentsCount = User::where('type', 'student')->count();
        $subjectsCount = Subject::all()->count();
        $adminsCount = User::where('type', 'admin')->count();

        // Get active batch students and teachers count
//        $activeBatchTeachersCount = User::with([
//            'teacher.batches' => function ($query) {
//                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
//            },
//        ])->where('type', 'teacher')->count();

        $levels = Level::with([
            'levelCategory',
            'batches' => function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            },
        ])->get();

        // Get active batch students count
//        $activeBatchStudentsCount = User::with([
//            'student.batches' => function ($query) {
//                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
//            },
//        ])->where('type', 'student')->count();

        $activeStudents = User::with('student.batches.activeBatch')->where('type', 'student')->count();

        // Get absentee records of active batch students
        $absenteeRecords = Absentee::with('user.student.batches.activeBatch')->count();

        // Get admins of active batch
        $admins = User::with('roles')->where('type', 'admin')->get();

        // Get all activity log for the logged in user
        $activityLogs = Activity::where('log_name', 'user')
            ->with(['causer' => function ($query) {
                $query->where('id', auth()->id());
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Index', [
            'teachers_count' => $teachersCount,
            'students_count' => $studentsCount,
            'subjects_count' => $subjectsCount,
            'admins_count' => $adminsCount,
            'levels' => $levels,
            //            'active_batch_teachers_count' => $activeBatchTeachersCount,
            //            'active_batch_students_count' => $activeBatchStudentsCount,
            'active_students' => $activeStudents,
            'absentee_records' => $absenteeRecords,
            'admins' => $admins,
            'activity_logs' => $activityLogs,
        ]);
    }

    public function schedule(Request $request): Response
    {
        $searchKey = $request->input('search');

        $schoolSchedule = SchoolSchedule::where('title', 'like', '%'.$searchKey.'%')->get();

        return Inertia::render('Admin/Schedules/Index', [
            'school_schedule' => $schoolSchedule,
        ]);
    }
}
