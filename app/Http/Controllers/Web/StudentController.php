<?php

namespace App\Http\Controllers\Web;

use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Level;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $searchKey = $request->input('search');

        $students = Student::with([
            'user:id,name,email,phone_number,gender',
            'batches.batch:id,section',
            'batches.level',
        ])->select('id', 'user_id')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })->paginate(15);

        // Get all batches
        $batches = Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->with('level')->get();

        $request->validate([
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        $batchId = $request->batch_id ?? Batch::active()->firstOrFail()->id;
        $selectedBatch = Batch::find($batchId)->load('level');

        $batchStudents = BatchStudent::where('batch_id', $batchId)->with([
            'student.user:id,name,email,phone_number,gender',
            'batch:id,section',
            'level',
        ])->get();

        return Inertia::render('Admin/Students/Index', [
            'students' => $students,
            'batches' => $batches,
            'selected_batch' => $selectedBatch,
            'batch_students' => $batchStudents,
        ]);
    }

    public function show(Student $student): Response
    {
        $student = $student->load(
            'user',
            'guardian',
            'guardian.user',
            'batches',
            'batches.batch',
            'batches.batch.level',
        );

        return Inertia::render('Students/Single', [
            'student' => $student,
            'schedule' => $student->activeBatch()->load(
                'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
                'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
                'schedule.batchSubject.subject',
                'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id'
            )->only('schedule')['schedule'],
            'active_batch' => $student->activeBatch(load: ['level', 'level.levelCategory']),
            'attendance' => [
                'absence_rate' => $student->absenteePercentage(),
                'absentee_records' => $student->absenteeRecords()->get(),
            ],
            'periods' => Level::find($student->activeBatch()->level->id)->levelCategory->schoolPeriods,
        ]);
    }
}
