<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\Semester;
use App\Models\Student as StudentModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Student extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StudentModel $student, Request $request): Response
    {
        $batchSubjectId = $request->query('batch_subject_id');

        $student = $student->load('user');
        $currentBatch = $student->activeBatch(['level', 'subjects']);
        $studentBatchSubjectIds = collect($currentBatch['subjects'])->pluck('id');
        $commonBatchSubject = BatchSubject::where('teacher_id', auth()->user()->teacher->id)
            ->whereIn('id', $studentBatchSubjectIds)
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')->get();

        $studentAssessment = $student->assessments()->orderBy('updated_at', 'DESC')
            ->whereRelation('assessment', 'batch_subject_id', $batchSubjectId)->get()->map(function ($studentAssessment) {
                $studentAssessment->assessment->point = $studentAssessment->point;

                return $studentAssessment->assessment;
            })->take(4);

        $student->conduct = $student->studentSubjectGrades()->where(
            'batch_subject_id',
            $batchSubjectId
        )->where('gradable_type', Quarter::class)->first()?->conduct;

        $student->absentee_percentage = $student->absenteePercentage();
        $student->assessment_quarter_grade = $student->fetchAssessmentsGrade($batchSubjectId, Quarter::getActiveQuarter()->id);
        $student->total_batch_subject_grade = $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first()?->score;
        $student->batch_subject_rank = $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first()?->rank;
        $student->quarterly_grade = $student->grades()->where([[
            'gradable_type', Quarter::class,
        ], [
            'gradable_id', Quarter::getActiveQuarter()->id,
        ]])->first();
        $student->semester_grade = $student->grades()->where([[
            'gradable_type', Semester::class,
        ], [
            'gradable_id', Semester::getActiveSemester()->id,
        ]])->first();

        return Inertia::render('Teacher/Student', [
            'student' => $student->load('user'),
            'guardian' => $student->load(
                'guardian:id,user_id',
                'guardian.user:id,name,email,phone_number,address_id',
                'guardian.user.address:id,sub_city,city,country,woreda,house_number',
            ),
            'assessments' => $studentAssessment,
            'current_batch' => $currentBatch,
            'attendance_percentage' => 100 - $student->absenteePercentage(),
            'schedule' => $student->activeBatch()->load(
                'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
                'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
                'schedule.batchSubject.subject',
                'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id'
            )->only('schedule')['schedule'],
            'periods' => Level::find($student->activeBatch()->level->id)->levelCategory->schoolPeriods,
            'batch_sessions' => $student->upcomingSessions(['batchSchedule.batchSubject.batch.level', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.subject', 'batchSchedule.batchSubject.teacher.user'])->get(),
            'batch_subject' => BatchSubject::find($batchSubjectId)->load('subject', 'batch.level'),
            'batch_subjects' => $commonBatchSubject,
            'batch_subject_grade' => $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first(),
            'total_batch_students' => $student->activeBatch()->students()->count(),
            'in_progress_session' => $currentBatch->inProgressSession()?->load('batchSchedule.batchSubject.subject', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.teacher.user'),
            'student_notes' => $student->notes()->orderBy('updated_at', 'DESC')->with('author:name,id,email,phone_number,gender')->get()->take(5),
        ]);
    }
}
