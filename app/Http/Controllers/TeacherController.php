<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(Request $request): Response
    {
        $searchKey = $request->input('search');

        $teachers = Teacher::with([
            'user:id,name,email,phone_number,gender',
            'batchSubjects:id,subject_id,batch_id,teacher_id',
            'batchSubjects.subject:id,full_name',
            'batchSubjects.batch:id,section,level_id',
            'batchSubjects.batch.level:id,name',
            'homeroom:id,batch_id,teacher_id',
            'homeroom.batch:id,section,level_id',
            'homeroom.batch.level:id,name',
        ])->select('id', 'user_id')
            ->when($searchKey, function ($query) use ($searchKey) {
                return $query->whereHas('user', function ($query) use ($searchKey) {
                    return $query->where('name', 'like', "%{$searchKey}%");
                });
            })
            ->paginate(15);

        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers,
        ]);
    }

    public function show(Request $request, int $id = null): Response
    {
        $id = $id ?? (auth()->user()->isTeacher() ? auth()->user()->teacher->id : abort(403));

        $batches = $this->getBatches($id);
        $students = $this->getStudents($id, $request->input('batch_subject_id'), $request->input('search'));
        $teacher = $this->getTeacherDetails($id);

        if (isset($teacher->nextBatchSession)) {
            // Get the last assessment of the next batch session using the batch subject id
            $lastAssessment = Assessment::where('batch_subject_id', $teacher->nextBatchSession->batchSubject->id)
                ->where('quarter_id', Quarter::getActiveQuarter()->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastAssessment) {
                $lastAssessment->load('assessmentType:id,name');
            }
        }

        $schoolScheduleDate = $request->input('school_schedule_date') ?? now();
        $schoolSchedule = SchoolSchedule::where('school_year_id', SchoolYear::getActiveSchoolYear()->id)
            ->whereDate('start_date', '<=', Carbon::parse($schoolScheduleDate))
            ->whereDate('end_date', '>=', Carbon::parse($schoolScheduleDate))
            ->orderBy('start_date', 'asc')
            ->take(2)
            ->get();

        return Inertia::render('Teacher/Index', [
            'teacher' => $teacher,
            'batches' => $batches,
            'assessment_type' => $batches->unique()->pluck('level.levelCategory.assessmentTypes')->unique()->flatten(),
            'students' => $students,
            'school_schedule' => $schoolSchedule,
            'school_schedule_date' => $schoolScheduleDate,
            'last_assessment' => $lastAssessment ?? null,
        ]);
    }

    public function assessments(Request $request): Response
    {
        $request->validate([
            'batch_subject_id' => 'nullable|integer|exists:batch_subjects,id',
            'assessment_type_id' => 'nullable|integer|exists:assessment_types,id',
            'due_date' => 'nullable|date',
            'quarter_id' => 'nullable|integer|exists:quarters,id',
            'semester_id' => 'nullable|integer|exists:semesters,id',
            'school_year_id' => 'nullable|integer|exists:school_years,id',
            'search' => 'nullable|string',
        ]);

        $batchSubjectId = $request->input('batch_subject_id') ??
            BatchSubject::where('teacher_id', auth()->user()->teacher->id)
                ->whereHas('batch', function ($query) {
                    $query->where('school_year_id', SchoolYear::getActiveSchoolYear()->id);
                })->first()->id;

        $quarters = Quarter::with('semester.schoolYear')->get();
        $semesters = Semester::with('schoolYear')->get();
        $schoolYears = SchoolYear::all();

        $assessments = Assessment::where('batch_subject_id', $batchSubjectId)
            ->when($request->input('assessment_type_id'), function ($query) use ($request) {
                return $query->where('assessment_type_id', $request->input('assessment_type_id'));
            })
            ->when($request->input('due_date'), function ($query) use ($request) {
                return $query->whereDate('due_date', $request->input('due_date'));
            })
            ->when($request->input('quarter_id'), function ($query) use ($request) {
                return $query->where('quarter_id', $request->input('quarter_id'));
            })
            ->when($request->input('semester_id'), function ($query) use ($request) {
                return $query->whereHas('quarter', function ($query) use ($request) {
                    return $query->where('semester_id', $request->input('semester_id'));
                });
            })
            ->when($request->input('school_year_id'), function ($query) use ($request) {
                return $query->whereHas('quarter.semester', function ($query) use ($request) {
                    return $query->where('school_year_id', $request->input('school_year_id'));
                });
            })
            ->when($request->input('search'), function ($query) use ($request) {
                return $query->where('title', 'like', "%{$request->input('search')}%");
            })
            ->with([
                'batchSubject:id,batch_id,subject_id,teacher_id',
                'assessmentType:id,name',
                'quarter:id,name',
            ])
            ->orderBy('due_date', 'desc')
            ->paginate(15);

        return Inertia::render('Teacher/Assessments/Index', [
            'assessments' => $assessments,
            'quarters' => $quarters,
            'semesters' => $semesters,
            'school_years' => $schoolYears,
        ]);
    }

    private function getBatches(int $id)
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

    private function getStudents(int $id, ?string $batchSubjectId, ?string $studentSearch)
    {
        // Get students of a teacher for specific batchSubjectId
        $batchSubjectId = $batchSubjectId ?? BatchSubject::where('teacher_id', $id)->first()->id;

        $students = Teacher::with([
            'batchSubjects' => function ($query) use ($batchSubjectId) {
                $query->where('id', $batchSubjectId);
            },
            'batchSubjects.students' => function ($query) use ($studentSearch) {
                $query->whereHas('user', function ($userQuery) use ($studentSearch) {
                    $userQuery->where('name', 'LIKE', '%'.$studentSearch.'%');
                })->take(6);
            },
            'batchSubjects.students.user',
            'batchSubjects.students.batches',
        ])->select('id', 'user_id')
            ->where('id', $id)
            ->first()
            ->batchSubjects
            ->map(function ($batchSubject) use ($studentSearch) {
                $students = $batchSubject->students->map(function ($student) {
                    $student->attendance_percentage = 100 - $student->absenteePercentage();

                    return $student;
                });

                return [
                    'batch_subject_id' => $batchSubject->id,
                    'students' => $students,
                    'search' => $studentSearch ?? '',
                ];
            });

        if ($students->isEmpty()) {
            return [
                [
                    'batch_subject_id' => (int) $batchSubjectId,
                    'students' => [],
                    'search' => $studentSearch ?? '',
                ],
            ];
        }

        return $students;
    }

    private function getTeacherDetails(int $id): Model|Collection|Builder|array|null
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
                $query->where('quarter_id', Quarter::getActiveQuarter()->id)->orderBy('created_at', 'desc')->limit(3);
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
}
