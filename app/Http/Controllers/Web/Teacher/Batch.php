<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\Assessment;
use App\Models\Batch as BatchModel;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\User;
use App\Services\StudentService;
use App\Services\TeacherService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Batch extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $id = auth()->user()->isTeacher() ? auth()->user()->teacher->id : $request->input('teacher_id');
        if (! $id) {
            abort(403);
        }

        $batchSubject = TeacherService::prepareBatchSubject($request, $id);
        $students = TeacherService::getStudents($batchSubject->id, $request->input('search'), $request);
        $teacher = Teacher::find($id)->load('user', 'homeroom.batch');

        $homeroomTeacher = Teacher::with('user')->whereHas('homeroom', function ($query) use ($batchSubject) {
            $query->where('batch_id', $batchSubject->batch_id);
        })->first();

        $batchSubjects = $teacher->batchSubjects()
            ->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')
            ->get();

        // Get lesson plan of the teacher and filter it by batch subject
        $lessonPlan = $batchSubject->sessions()->whereHas('lessonPlan')->with('lessonPlan', 'batchSchedule.batch.level', 'batchSchedule.batchSubject.subject')->get()->take(4);

        // Get assessments of the teacher and filter it by batch subject
        $assessments = Assessment::where('batch_subject_id', $batchSubject->id)
            ->where('status', '!=', Assessment::STATUS_DRAFT)
            ->where('quarter_id', Quarter::getActiveQuarter()->id)
            ->orderBy('updated_at', 'desc')
            ->with('assessmentType:id,name',
                'batchSubject.batch:id,section,level_id',
                'batchSubject.batch.level:id,name,level_category_id',
                'batchSubject.subject:id,full_name')
            ->get()->take(3);

        $batch = BatchModel::find($batchSubject->batch_id)->load('level:id,name,level_category_id', 'level.levelCategory:id,name');
        $schedule = BatchModel::find($batchSubject->batch_id)->load(
            'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
            'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
            'schedule.batchSubject.subject',
            'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id',
        )->only('schedule')['schedule'];

        $page = match (auth()->user()->type) {
            User::TYPE_TEACHER => 'Teacher/Batches',
            User::TYPE_ADMIN => 'Admin/Teachers/Single',
            default => throw new Exception('Type unknown!'),
        };

        return Inertia::render($page, [
            'students' => $students,
            'batch_subject' => $batchSubject,
            'batch_subjects' => $batchSubjects,
            'lesson_plans' => $lessonPlan,
            'assessments' => $assessments,
            'schedule' => $schedule,
            'periods' => Level::find($batch->level->id)->levelCategory->schoolPeriods,
            'grade' => $batchSubject->batchGrades()->where([
                ['gradable_type', Quarter::class],
                ['gradable_id', Quarter::getActiveQuarter()->id],
            ])->first(),
            'in_progress_session' => $batch->inProgressSession()?->load('batchSchedule.batchSubject.subject', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.teacher.user', 'batchSchedule.batchSubject.batch.level'),
            'top_students' => StudentService::getBatchSubjectTopStudents($batchSubject),
            'bottom_students' => StudentService::getBatchSubjectBottomStudents($batchSubject),
            'teacher' => $teacher,
            'filters' => [
                'batch_subject_id' => $batchSubject->id,
                'search' => $request->input('search'),
            ],
            'homeroom_teacher' => $homeroomTeacher,
            'batches' => TeacherService::assignHomeroomTeacherData(),
        ]);
    }
}
