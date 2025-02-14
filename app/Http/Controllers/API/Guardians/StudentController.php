<?php

namespace App\Http\Controllers\API\Guardians;

use App\Http\Requests\API\Students\AssessmentRequest;
use App\Http\Requests\API\Students\BatchSubjectRequest;
use App\Http\Requests\API\Students\GradeRequest;
use App\Http\Requests\API\Students\Request;
use App\Http\Requests\API\Students\SubjectAssessmentsRequest;
use App\Http\Requests\API\Students\TermRequest;
use App\Http\Resources\Guardians\Student\AssessmentCollection;
use App\Http\Resources\Guardians\Student\AssessmentGradeCollection;
use App\Http\Resources\Guardians\Student\AssessmentGradeResource;
use App\Http\Resources\Guardians\Student\AssessmentResource;
use App\Http\Resources\Guardians\Student\AssessmentTypeCollection;
use App\Http\Resources\Guardians\Student\AssessmentTypeResource;
use App\Http\Resources\Guardians\Student\BatchSubjectResource;
use App\Http\Resources\Guardians\Student\Collection;
use App\Http\Resources\Guardians\Student\GradeCollection;
use App\Http\Resources\Guardians\Student\GradeResource;
use App\Http\Resources\Guardians\Student\NoteCollection;
use App\Http\Resources\Guardians\Student\NoteResource;
use App\Http\Resources\Guardians\Student\Resource;
use App\Http\Resources\Guardians\Student\ScheduleCollection;
use App\Http\Resources\Guardians\Student\ScheduleResource;
use App\Http\Resources\Guardians\Student\SessionCollection;
use App\Http\Resources\Guardians\Student\SessionResource;
use App\Http\Resources\Guardians\Student\StudentAssessmentsGradeCollection;
use App\Http\Resources\Guardians\Student\SubjectCollection;
use App\Http\Resources\Guardians\Student\SubjectResource;
use App\Http\Resources\Guardians\Term\TermCollection;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAssessmentsGrade;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request, ?Student $student): Resource|Collection
    {
        return $student->exists ?
            new Resource($student->load('user', 'user.address')) :
            new Collection(Auth::user()->load(
                'guardian.children.user',
                'guardian.children.user.address',
                'guardian.children.currentBatch.homeRoomTeacher.teacher.user'
            )->guardian->children);
    }

    public function notes(Request $request, ?Student $student): NoteResource|NoteCollection
    {
        return $student->exists ?
            new NoteResource($student->load(
                'user',
                'studentNotes.author',
            )) :
            new NoteCollection(Auth::user()->load('guardian.children')
                ->guardian->children->load(
                    'user',
                    'studentNotes.author',
                ));
    }

    public function schedules(Request $request, ?Student $student): ScheduleResource|ScheduleCollection
    {
        return $student->exists ?
            new ScheduleResource($student->load(
                'user',
                'currentBatch.schedule.batchSubject.subject',
                'currentBatch.schedule.batchSubject.teacher.user')) :
            new ScheduleCollection(Auth::user()
                ->load('guardian.children')->guardian->children
                ->load(
                    'user',
                    'currentBatch.schedule.batchSubject.subject',
                    'currentBatch.schedule.batchSubject.teacher.user'
                ));
    }

    public function batchAssessments(AssessmentRequest $request, ?Student $student): AssessmentResource|AssessmentCollection
    {
        return $student->exists ?
            new AssessmentResource($student->load([
                'user',
                'currentBatch.subjects.assessments' => function ($query) {
                    $query->orderBy('due_date', 'desc');
                },
                'currentBatch.subjects.assessments.assessmentType',
                'currentBatch.subjects.assessments.batchSubject.subject'])) :
            new AssessmentCollection(Auth::user()
                ->load([
                    'guardian.children.user',
                    'currentBatch.subjects.assessments' => function ($query) {
                        $query->orderBy('due_date', 'desc');
                    },
                    'guardian.children.currentBatch.subjects.assessments.assessmentType',
                    'guardian.children.currentBatch.subjects.assessments.batchSubject.subject',
                ])
                ->guardian->children
            );
    }

    public function assessments(Request $request, ?Student $student): AssessmentGradeResource|AssessmentGradeCollection
    {
        if ($student->exists) {
            $student = $student->load([
                'user',
                'assessments' => function ($query) use ($request, $student) {
                    $this->filterAssessments($query, $request, $student);
                },
            ]);

            return new AssessmentGradeResource($student);
        }

        $students = Auth::user()->load('guardian.children')->guardian->children->load([
            'user',
            'assessments' => function ($query) use ($request) {
                $this->filterAssessments($query, $request);
            },
        ]);

        return new AssessmentGradeCollection($students);
    }

    public function assessmentTypes(Request $request, ?Student $student): AssessmentTypeResource|AssessmentTypeCollection
    {
        return $student->exists ?
            new AssessmentTypeResource($student->load([
                'currentBatch.level.levelCategory.assessmentTypes',
            ])
                ->currentBatch->first()->level
                ->levelCategory->assessmentTypes) :
            new AssessmentTypeCollection(Auth::user()->load([
                'guardian.children.currentBatch.level.levelCategory.assessmentTypes',
            ])
                ->guardian->children->pluck('currentBatch')
                ->flatten()->pluck('level')->pluck('levelCategory')
                ->pluck('assessmentTypes')->flatten()
            );
    }

    public function subjects(Request $request, ?Student $student): SubjectResource|SubjectCollection
    {
        return $student->exists ?
            new SubjectResource($student->load(
                'user',
                'currentBatch.subjects.subject',
                'currentBatch.subjects.teacher.user'
            )) :
            new SubjectCollection(Auth::user()->load(
                'guardian.children.user',
                'guardian.children.currentBatch.subjects.subject',
                'guardian.children.currentBatch.subjects.teacher.user'
            )->guardian->children);
    }

    public function batchSubjectGrades(BatchSubjectRequest $request, Student $student, BatchSubject $batchSubject): BatchSubjectResource
    {
        return $student->exists ?
            new BatchSubjectResource($student->load([
                'assessments' => function ($query) use ($batchSubject) {
                    $query->join('assessments', 'assessments.id', '=', 'student_assessments.assessment_id')
                        ->select('student_assessments.*', 'student_assessments.id AS student_assessments_id')
                        ->where('batch_subject_id', $batchSubject->id)
                        ->orderBy('due_date', 'desc');
                    $query->take(3);
                },
            ])) :
            new BatchSubjectResource(Auth::user()->load('guardian.children')->guardian->children->load([

            ]));
    }

    public function sessions(Request $request, ?Student $student): SessionResource|SessionCollection
    {
        return $student->exists ?
            new SessionResource($student->load(
                'user',
                'currentBatch.weeklySessions.attendances',
                'currentBatch.weeklySessions.schoolPeriod',
                'currentBatch.weeklySessions.lessonPlan',
                'currentBatch.weeklySessions.batchSchedule',
                'currentBatch.weeklySessions.teacher.user',
                'currentBatch.weeklySessions.batchSubject.subject',
            )) :
            new SessionCollection(Auth::user()
                ->load('guardian.children')->guardian->children
                ->load(
                    'user',
                    'currentBatch.weeklySessions.attendances',
                    'currentBatch.weeklySessions.schoolPeriod',
                    'currentBatch.weeklySessions.lessonPlan',
                    'currentBatch.weeklySessions.batchSchedule',
                    'currentBatch.weeklySessions.teacher.user',
                    'currentBatch.weeklySessions.batchSubject.subject',
                ));
    }

    public function terms(TermRequest $request, Student $student): TermCollection
    {
        $terms = $student->load('grades.gradable')->grades->sortByDesc('gradable.start_date')->pluck('gradable');

        return new TermCollection(match ($request->input('type')) {
            'quarter' => $terms->filter(fn ($term) => $term instanceof Quarter)->flatten(),
            'semester' => $terms->filter(fn ($term) => $term instanceof Semester)->flatten(),
            'school_year' => $terms->filter(fn ($term) => $term instanceof SchoolYear)->flatten(),
        });
    }

    public function grades(GradeRequest $request, ?Student $student): GradeResource|GradeCollection
    {
        return $student->exists ?
            new GradeResource($student->load([
                'user:id,name,profile_image',
                'grades' => function ($query) use ($request) {
                    $query->when($request->input('gradable_type'), function ($query) use ($request) {
                        $query->where('gradable_type', $request->input('gradable_type'));

                        // If "active" filter is set, filter by the active gradable
                        $query->when($request->input('active'), function ($query) use ($request) {
                            $gradable_id = match ($request->input('gradable_type')) {
                                'App\Models\Quarter' => Quarter::getActiveQuarter()->id,
                                'App\Models\Semester' => Semester::getActiveSemester()->id,
                                'App\Models\SchoolYear' => SchoolYear::getActiveSchoolYear()->id,
                            };
                            $query->where('gradable_id', $gradable_id);
                        });
                    }, function ($query) use ($request) {
                        // If "active" filter is set, filter by the active gradable
                        $query->when($request->input('active'), function ($query) {
                            $query->where([
                                'gradable_type' => 'App\Models\SchoolYear',
                                'gradable_id' => SchoolYear::getActiveSchoolYear()->id,
                            ])
                                ->orWhere([
                                    'gradable_type' => 'App\Models\Semester',
                                    'gradable_id' => Semester::getActiveSemester()->id,
                                ])
                                ->orWhere([
                                    'gradable_type' => 'App\Models\Quarter',
                                    'gradable_id' => Quarter::getActiveQuarter()->id,
                                ]);
                        });
                    });
                },
                'grades.gradable:id,name',
                'grades.gradeScale:id,label,state,description',
            ])) :
            new GradeCollection(Auth::user()
                ->load('guardian.children')->guardian->children
                ->load([
                    'user:id,name,profile_image',
                    'grades' => function ($query) use ($request) {
                        $query->when($request->input('gradable_type'), function ($query) use ($request) {
                            $query->where('gradable_type', $request->input('gradable_type'));
                        });
                    },
                    'grades.gradable',
                    'grades.gradeScale:id,label,state,description',
                ]));
    }

    public function subjectAssessments(SubjectAssessmentsRequest $request, BatchSubject $batchSubject, Student $student): StudentAssessmentsGradeCollection
    {
        $gradable_type = $request->input('gradable_type');
        $gradable_id = $request->input('gradable_id');

        return new StudentAssessmentsGradeCollection(StudentAssessmentsGrade::where([
            'student_id' => $student->id,
            'batch_subject_id' => $batchSubject->id,
            'gradable_type' => $gradable_type,
            'gradable_id' => $gradable_id,
        ])->with('assessmentType', 'gradeScale')->get());
    }

    private function filterAssessments($query, $request, $student = null): void
    {
        $query->with([
            'assessment.batchSubject.batch.schoolYear',
            'assessment.batchSubject.teacher.user',
        ]);
        $query->join('assessments', 'assessments.id', '=', 'student_assessments.assessment_id')
            ->select('student_assessments.*', 'student_assessments.id AS student_assessments_id')
            ->when($request->has('student_id'), function ($query) use ($request) {
                $query->whereIn('student_id', $request->input('student_id'), 'AND');
            })
            ->when($request->has('status'), function ($query) use ($request) {
                $query->whereIn('assessments.status', $request->input('status'), 'AND');
            })
            ->when($request->has('assessment_type_id'), function ($query) use ($request) {
                $query->whereIn('assessment_type_id', $request->input('assessment_type_id'), 'AND');
            })
            ->when($request->has('batch_subject_id'), function ($query) use ($request) {
                $query->whereIn('batch_subject_id', $request->input('batch_subject_id'), 'AND');
            })
            ->when($request->has('query'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('assessments.title', 'LIKE', '%'.$request->input('query').'%');
                    $query->orWhere('assessments.description', 'LIKE', '%'.$request->input('query').'%');
                    $query->orWhereRelation('assessment.batchSubject.subject', 'full_name', 'LIKE', '%'.$request->input('query').'%');
                });
            })
            ->orderBy('due_date', 'desc');
    }
}
