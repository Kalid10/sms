<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchSession;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Teacher;
use Carbon\Carbon;
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

    public function getLessonPlansData(Request $request, $teacherId): array
    {
        $batchSubjectId = $request->input('batch_subject_id') ?? BatchSubject::where('teacher_id', $teacherId)
            ->whereHas('batch', function ($query) {
                $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
            })->first()?->id;

        $batchSubject = BatchSubject::with([
            'subject:id,full_name',
            'batch:id,section,level_id',
            'batch.level:id,name',
        ])->where([
            ['id', $batchSubjectId],
            ['teacher_id', $teacherId],
        ])->firstOrFail();

        $batchId = $batchSubject->batch_id;
        $currentMonth = $request->input('month') ? Carbon::createFromFormat('Y-m', $request->input('month')) : Carbon::now();
        $firstDayOfMonth = $currentMonth->copy()->startOfMonth();
        $lastDayOfMonth = $currentMonth->copy()->endOfMonth();

        $teacherSubjects = BatchSubject::with([
            'subject:id,full_name',
            'batch:id,section,level_id',
            'batch.level:id,name,level_category_id',
        ])->where('teacher_id', $teacherId)
            ->whereHas('batch', fn ($query) => $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id))
            ->distinct()
            ->get(['id', 'subject_id', 'batch_id']);

        $quarterFilter = $request->input('quarter_id');

        $batchSessionsWithLessonPlans = BatchSession::where('teacher_id', $teacherId)
            ->whereHas('batchSchedule.batchSubject', fn ($query) => $query->where('batch_subject_id', $batchSubject->id))
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->when($quarterFilter, function (Builder $query) use ($quarterFilter) {
                $query->whereHas('batchSchedule', function (Builder $query) use ($quarterFilter) {
                    $query->where('quarter_id', $quarterFilter);
                });
            })
            ->with([
                'batchSchedule.schoolPeriod',
                'batchSchedule.batchSubject.subject',
                'lessonPlan',
            ])
            ->get()
            ->sortBy('date')
            ->groupBy(function ($batchSession) use ($firstDayOfMonth) {
                $date = Carbon::parse($batchSession->date);
                $weekNumber = $date->weekOfYear - $firstDayOfMonth->weekOfYear + 1;

                return 'week'.max($weekNumber, 1);
            });

        $weeksInMonth = (int) ($currentMonth->daysInMonth / 7) + ($currentMonth->daysInMonth % 7 != 0 ? 1 : 0);

        $weeklyBatchSessions = array_fill_keys(array_map(fn ($i) => "week{$i}", range(1, $weeksInMonth)), collect([]));

        foreach ($batchSessionsWithLessonPlans as $weekKey => $batchSessions) {
            $weeklyBatchSessions[$weekKey] = $batchSessions;
        }

        $months = collect(range(1, 12))->map(function ($month) {
            return [
                'value' => Carbon::today()->month($month)->format('Y-m'),
                'label' => Carbon::today()->month($month)->format('F'),
            ];
        });

        $quarters = Quarter::with('semester.schoolYear')->get();
        $semesters = Semester::with('schoolYear')->get();
        $schoolYears = SchoolYear::all();

        return [
            'quarters' => $quarters,
            'semesters' => $semesters,
            'school_years' => $schoolYears,
            'batch_sessions' => $weeklyBatchSessions,
            'batch' => Batch::find($batchId)->load('level'),
            'subjects' => $teacherSubjects,
            'weeks_in_month' => $weeksInMonth,
            'lesson_plan_subject' => $batchSubject,
            'months' => $months,
            'selected_month' => $currentMonth->format('Y-m'),
            'teacher' => Teacher::find($teacherId)->load('user'),
        ];
    }

    public static function getTeacherAbsentee(int $id, int $schoolYearId): array
    {
        $teacher = Teacher::findOrFail($id);

        $absentee = $teacher->staffAbsenteePercentage($schoolYearId);

        return [
            'absentee' => $absentee,
        ];
    }
}
