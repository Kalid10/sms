<?php

namespace App\Http\Controllers\Web\Teacher;

use App\Models\BatchSubject;
use App\Models\Level;
use App\Models\Quarter;
use App\Models\Semester;
use App\Models\Student as StudentModel;
use App\Models\StudentNote;
use App\Models\User;
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
        $student = $this->loadStudentData($student, $batchSubjectId);
        $studentAssessment = $this->prepareStudentAssessment($student, $batchSubjectId);
        $currentBatch = $this->getCurrentBatch($student);
        $batchSubjects = $this->getBatchSubjects($currentBatch, $student, $batchSubjectId);

        return Inertia::render('Teacher/Student', [
            'student' => $student,
            'guardian' => $this->loadGuardianData($student),
            'assessments' => $studentAssessment,
            'current_batch' => $currentBatch,
            'attendance_percentage' => 100 - $student->absenteePercentage(),
            'schedule' => $this->getSchedule($student),
            'periods' => Level::find($student->activeBatch()->level->id)->levelCategory->schoolPeriods,
            'batch_sessions' => $this->getBatchSessions($student),
            'batch_subject' => BatchSubject::find($batchSubjectId)?->load('subject', 'batch.level'),
            'batch_subjects' => $batchSubjects,
            'batch_subject_grade' => $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first(),
            'total_batch_students' => $student->activeBatch()->students()->count(),
            'in_progress_session' => $currentBatch->inProgressSession()?->load('batchSchedule.batchSubject.subject', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.teacher.user'),
            'student_notes' => StudentNote::where('student_id', $student->id)->with('author:name,id,email,phone_number,gender')->paginate(5)->appends($request->all()),
        ]);
    }

    private function loadStudentData(StudentModel $student, $batchSubjectId): StudentModel
    {
        $student = $student->load('user');

        if ($batchSubjectId) {
            $student->conduct = $student->studentSubjectGrades()->where('batch_subject_id', $batchSubjectId)->where('gradable_type', Quarter::class)->first()?->conduct;
            $student->assessment_quarter_grade = $student->fetchAssessmentsGrade($batchSubjectId, Quarter::getActiveQuarter()->id);
            $student->total_batch_subject_grade = $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first()?->score;
            $student->batch_subject_rank = $student->fetchStudentBatchSubjectGrade($batchSubjectId, Quarter::getActiveQuarter()->id)->first()?->rank;
        } else {
            $student->conduct = $student->grades()->where('gradable_type', Quarter::class)->first()?->conduct;
        }

        $student->absentee_percentage = $student->absenteePercentage();
        $student->quarterly_grade = $student->grades()->where([['gradable_type', Quarter::class], ['gradable_id', Quarter::getActiveQuarter()->id]])->first();
        $student->semester_grade = $student->grades()->where([['gradable_type', Semester::class], ['gradable_id', Semester::getActiveSemester()->id]])->first();

        return $student;
    }

    private function prepareStudentAssessment($student, $batchSubjectId)
    {
        $studentAssessment = $student->assessments()->orderBy('updated_at', 'DESC');

        if (auth()->user()->type === User::TYPE_ADMIN) {
            $studentAssessment = $studentAssessment->get()->map(function ($studentAssessment) {
                $studentAssessment->assessment->point = $studentAssessment->point;

                return $studentAssessment->assessment;
            })->take(4);
        } else {
            $studentAssessment = $studentAssessment->whereRelation('assessment', 'batch_subject_id', $batchSubjectId)->get()->map(function ($studentAssessment) {
                $studentAssessment->assessment->point = $studentAssessment->point;

                return $studentAssessment->assessment;
            })->take(4);
        }

        return $studentAssessment;
    }

    private function loadGuardianData($student)
    {
        return $student->load(
            'guardian:id,user_id',
            'guardian.user:id,name,email,phone_number,address_id',
            'guardian.user.address:id,sub_city,city,country,woreda,house_number',
        );
    }

    private function getCurrentBatch($student)
    {
        return (auth()->user()->type === User::TYPE_ADMIN)
            ? $student->currentBatch()->first()
            : $student->activeBatch(['level', 'subjects']);
    }

    private function getSchedule($student)
    {
        return $student->activeBatch()->load(
            'schedule:id,school_period_id,batch_subject_id,day_of_week,batch_id',
            'schedule.batchSubject:id,teacher_id,subject_id,weekly_frequency',
            'schedule.batchSubject.subject',
            'schedule.schoolPeriod:id,name,start_time,duration,is_custom,level_category_id'
        )->only('schedule')['schedule'];
    }

    private function getBatchSessions($student)
    {
        return $student->upcomingSessions(['batchSchedule.batchSubject.batch.level', 'batchSchedule.schoolPeriod', 'batchSchedule.batchSubject.subject', 'batchSchedule.batchSubject.teacher.user'])->get();
    }

    private function getBatchSubjects($currentBatch, $student, $batchSubjectId)
    {
        return (auth()->user()->type === User::TYPE_ADMIN)
            ? BatchSubject::where('batch_id', $currentBatch->id)?->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')->get()
            : $this->getBatchSubjectsForNonAdmin($student, $batchSubjectId);
    }

    private function getBatchSubjectsForNonAdmin($student, $batchSubjectId)
    {
        $studentBatchSubjectIds = collect($student->activeBatch()['subjects'])->pluck('id');

        return BatchSubject::where('teacher_id', auth()->user()->teacher->id)
            ->whereIn('id', $studentBatchSubjectIds)
            ->with('subject:id,full_name', 'batch:id,section,level_id', 'batch.level:id,name')->get();
    }
}
