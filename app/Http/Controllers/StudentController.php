<?php

namespace App\Http\Controllers;

use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index()
    {
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

    public function teacherShow(Student $student): Response
    {
        $student = $student->load('user');
        $studentBatch = $student->activeBatch([]);

        $teacherStudentAssessment = BatchSubject::with('assessments')
            ->where('batch_id', $studentBatch->id)->get();

        return Inertia::render('Teacher/Student', [
            'student' => $student->load('user'),
            'assessments' => $teacherStudentAssessment,
        ]);
    }
}
