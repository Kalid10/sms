<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TeacherService
{
    public static function getTeacherDetails(int $id): Model|Collection|Builder|array|null
    {
        return Teacher::with([
            'user:id,name,email,phone_number,gender',
            'homeroom:id,batch_id,teacher_id',
            'homeroom.batch:id,section,level_id',
            'homeroom.batch.level:id,name',
            'batchSchedules',
            'batchSchedules.schoolPeriod:id,name,start_time,duration',
            'batchSchedules.batchSubject:id,subject_id,batch_id',
            'batchSchedules.batchSubject.subject:id,full_name',
            'batchSchedules.batchSubject.batch:id,section,level_id',
            'batchSchedules.batchSubject.batch.level:id,name',
            'batchSubjects' => function ($query) {
                $query->whereHas('batch', function ($batchQuery) {
                    $batchQuery->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                });
                $query->orderBy('updated_at', 'desc')->limit(6);
            },
            'batchSubjects.subject:id,full_name',
            'batchSubjects.batch:id,section,level_id,school_year_id',
            'batchSubjects.batch.level:id,name,level_category_id',
            'batchSubjects.batch.level.levelCategory:id,name',
            'feedbacks' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(2);
            },
            'feedbacks.author:id,name',
            'batchSubjects.students.user',
            'assessments' => function ($query) {
                $query->where('quarter_id', Quarter::getActiveQuarter()->id)
                    ->where('status', '!=', Assessment::STATUS_DRAFT)
                    ->orderBy('updated_at', 'DESC')->limit(3);
            },
            'assessments.assessmentType',
            'assessments.batchSubject.batch:id,section,level_id',
            'assessments.batchSubject.batch.level:id,name,level_category_id',
            'assessments.batchSubject.subject:id,full_name',
            'nextBatchSession.schoolPeriod:name',
            'nextBatchSession.batchSubject.batch:id,section,level_id',
            'nextBatchSession.batchSubject.batch.level:id,name',
            'nextBatchSession.batchSubject.subject:id,full_name',
            'nextBatchSession.lessonPlan:id',
            'lessonPlans' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(4);
            },
            'lessonPlans.batchSchedule.batch.level',
            'lessonPlans.batchSchedule.batchSubject.subject',
        ])->select('id', 'user_id')->findOrFail($id);
    }

    public function getBatches(int $id)
    {
        return Batch::with([
            'level:id,name,level_category_id',
            'level.levelCategory:id,name',
            'level.levelCategory.assessmentTypes',
        ])->whereHas('subjects', function ($query) use ($id) {
            $query->where('teacher_id', $id);
        })->where('school_year_id', SchoolYear::getActiveSchoolYear()->id)->distinct()->get(['id', 'section', 'level_id'])
            ->map(function ($batch) {
                $batch->full_name = $batch->level->name.' - '.$batch->section;

                return $batch;
            });
    }

    public static function getStudents(int $batchSubjectId, ?string $studentSearch, Request $request)
    {
        $schoolYearId = $request->input('school_year_id');

        $batchSubject = $batchSubjectId ?
            BatchSubject::find($batchSubjectId)->load('subject', 'batch.level') :
            BatchSubject::where('teacher_id', auth()->user()->teacher->id)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()->load('subject', 'batch.level')
                ->when($schoolYearId, function ($query) use ($schoolYearId) {
                    $query->whereHas('batch', function ($query) use ($schoolYearId) {
                        $query->where('school_year_id', $schoolYearId);
                    });
                });

        return StudentService::getBatchStudents($batchSubject->batch_id, $studentSearch, $batchSubjectId);
    }

    public static function prepareBatchSubject(Request $request, $teacherId): BatchSubject
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'search' => 'nullable|string',
        ]);

        $batchSubjectId = $request->input('batch_subject_id');

        if (! $batchSubjectId && ! $teacherId) {
            abort(403);
        }

        return $batchSubjectId ?
            BatchSubject::find($request->input('batch_subject_id'))->load('subject', 'batch.level') :
            BatchSubject::where('teacher_id', $teacherId)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()?->load('subject', 'batch.level');
    }

    public static function getTeacherFeedbacks(Teacher $teacher, int $limit = 5): LengthAwarePaginator
    {
        return $teacher->feedbacks()->with('author:id,name', 'author.admin')->orderBy('created_at', 'desc')->paginate($limit)->appends(request()->query());
    }

    public static function assignHomeroomTeacherData()
    {
        $searchKey = request()->query('search');

        return Batch::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->with('level', 'homeroomTeacher.teacher.user:id,name')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereRelation('level', 'name', 'like', "%{$searchKey}%");
            })->get();
    }
}
