<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\Assessments\AssessmentRequest;
use App\Http\Requests\API\Teachers\Assessments\MarkAssessmentRequest;
use App\Http\Requests\API\Teachers\Assessments\UpdateAssessmentRequest;
use App\Http\Requests\API\Teachers\Assessments\UpdateAssessmentStatusRequest;
use App\Http\Requests\Teachers\CreateAssessmentRequest;
use App\Http\Resources\Teachers\AssessmentCollection;
use App\Http\Resources\Teachers\AssessmentResource;
use App\Http\Resources\Teachers\StudentAssessmentCollection;
use App\Jobs\InsertStudentsAssessmentsJob;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\SchoolYear;
use App\Models\StudentAssessment;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;

class AssessmentController extends Controller
{
    /**
     * Return Assessment(s).
     */
    public function index(AssessmentRequest $request, ?Assessment $assessment): AssessmentCollection|AssessmentResource
    {
        if ($assessment->exists) {

            return new AssessmentResource($assessment->load([
                'quarter.semester',
                'batchSubject.batch.schoolYear',
                'batchSubject.batch.level.levelCategory',
                'batchSubject.subject',
                'assessmentType',
            ]));

        }

        $teacher = auth()->user()->load('teacher')->teacher;

        $assessments = $teacher
            ->load(
                'assessments.quarter.semester',
                'assessments.batchSubject.batch.schoolYear',
                'assessments.batchSubject.batch.level.levelCategory',
                'assessments.batchSubject.subject',
                'assessments.assessmentType'
            )
            ->assessments
            ->whereIn('status', $request->input('status', [
                Assessment::STATUS_DRAFT,
                Assessment::STATUS_SCHEDULED,
                Assessment::STATUS_PUBLISHED,
                Assessment::STATUS_MARKING,
                Assessment::STATUS_COMPLETED,
            ]))
            ->when($request->has('active'), function ($query) use ($request) {

                if ($request->input('active')) {
                    $query->where(function ($query) {
                        $this->filterActiveAssessments($query);
                    });
                }

            }, function ($query) {
                $query->where(function ($query) {
                    $this->filterActiveAssessments($query);
                });
            });

        return new AssessmentCollection($assessments);

    }

    /**
     * Return the Students of an Assessment.
     */
    public function students(AssessmentRequest $request, Assessment $assessment): StudentAssessmentCollection
    {
        $studentAssessments = StudentAssessment::with([
            'assessment.quarter.semester',
            'assessment.batchSubject.batch.schoolYear',
            'assessment.batchSubject.batch.level.levelCategory',
            'assessment.batchSubject.subject',
            'student.user',
        ])->where([
            'assessment_id' => $assessment->id,
        ])->get();

        return new StudentAssessmentCollection($studentAssessments);
    }

    /**
     * Create an Assessment.
     */
    public function create(CreateAssessmentRequest $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        foreach ($request->input('batch_subject_ids') as $batchSubjectId) {
            $batchSubject = BatchSubject::find($batchSubjectId);
            $batchSubject->assessments()->create(array_merge(
                $request->validated(),
                ['quarter_id' => Quarter::getActiveQuarter()->id],
                ['batch_subject_id' => $batchSubjectId],
                ['created_by' => auth()->user()->id],
            ));
        }

        return response([
            'message' => 'Assessment successfully created.',
        ], 201);
    }

    /**
     * Update an Assessment.
     */
    public function updateAssessment(UpdateAssessmentRequest $request, Assessment $assessment): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $assessment->update($request->validated());

        return response([
            'message' => 'Assessment successfully updated.',
        ], 200);
    }

    /**
     * Update an Assessment's status
     */
    public function updateStatus(UpdateAssessmentStatusRequest $request, Assessment $assessment): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $assessment->status = $request->input('new_status');
        $assessment->save();

        return response([
            'message' => `Assessment successfully status set to ${$request->input('new_status')}`,
        ], 200);
    }

    /**
     * Post Students' Assessment marks.
     */
    public function mark(MarkAssessmentRequest $request, Assessment $assessment): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        InsertStudentsAssessmentsJob::dispatch($request->validated('points'), $assessment);

        return response([
            'message' => 'Your students\'s assessments are being inserted. We will notify you when it\'s done.',
        ], 200);
    }

    /**
     * Create a "Classwork" Assessment.
     */
    public function createClassworkAssessment(\App\Http\Requests\API\Teachers\Assessments\CreateAssessmentRequest $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $batchSubjectId = $request->input('batch_subject_id');
        $batchSubject = BatchSubject::find($batchSubjectId);
        $type = $request->input('type');
        $assessmentTypeId = $batchSubject->level->levelCategory->assessmentTypes->where('name', $type)->first()->id;
        $isAssessmentTypeValid = $this->isAssessmentTypeValid($request);

        $schoolPeriod = $batchSubject->load('inProgressSession.batchSchedule.schoolPeriod')
            ->inProgressSession->batchSchedule->schoolPeriod;

        [$hours, $minutes, $seconds] = explode(':', $schoolPeriod->start_time);

        $deadline = Carbon::today()->setTime($hours, $minutes, $seconds);
        $deadline->addMinutes($schoolPeriod->duration);

        $batchSubject->assessments()->create(array_merge(
            $request->validated(),
            [
                'quarter_id' => Quarter::getActiveQuarter()->id,
                'batch_subject_id' => $batchSubjectId,
                'created_by' => auth()->user()->id,
                'assessment_type_id' => $assessmentTypeId,
                'due_date' => $deadline,
                'status' => 'scheduled',
            ]
        ));

        return response([
            'message' => 'Classwork Assessment successfully created.',
        ], $isAssessmentTypeValid['status'] ? 201 : 403);
    }

    /**
     * Create a "Homework" Assessment.
     */
    public function createHomeworkAssessment(\App\Http\Requests\API\Teachers\Assessments\CreateAssessmentRequest $request): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $batchSubjectId = $request->input('batch_subject_id');
        $batchSubject = BatchSubject::find($batchSubjectId);
        $type = $request->input('type');
        $assessmentTypeId = $batchSubject->level->levelCategory->assessmentTypes->where('name', $type)->first()->id;

        $isAssessmentTypeValid = $this->isAssessmentTypeValid($request);

        $batchSubject->assessments()->create(array_merge(
            $request->validated(),
            ['quarter_id' => Quarter::getActiveQuarter()->id],
            ['batch_subject_id' => $batchSubjectId],
            ['created_by' => auth()->user()->id],
            ['assessment_type_id' => $assessmentTypeId],
            ['status' => 'scheduled'],
        ));

        return response([
            'message' => 'Homework Assessment successfully created.',
        ], $isAssessmentTypeValid['status'] ? 201 : 403);
    }

    /**
     * Filter the active Assessments.
     */
    private function filterActiveAssessments($query): void
    {
        $query->whereHas('quarter', function ($query) {
            $query->whereNull('end_date');
        });
    }

    /**
     * Check if Assessment type is valid.
     */
    private function isAssessmentTypeValid(\App\Http\Requests\API\Teachers\Assessments\CreateAssessmentRequest $request): array
    {
        $batchSubjectId = $request->input('batch_subject_id');
        $batchSubject = BatchSubject::find($batchSubjectId);
        $type = $request->input('type');
        $assessmentTypeId = $batchSubject->level->levelCategory->assessmentTypes->where('name', $type)->first()->id;

        // Check if assessment type is active
        $assessmentType = AssessmentType::find($assessmentTypeId);
        if ($assessmentType->school_year_id !== SchoolYear::getActiveSchoolYear()->id) {
            return [
                'status' => false,
                'error' => 'Invalid assessment type.',
            ];
        }

        // If assessment type is not customizable, check if it has reached its maximum count for this quarter
        if (! $assessmentType->customizable) {
            $assessments = Assessment::where([
                ['assessment_type_id', $assessmentTypeId],
                ['batch_subject_id', $batchSubjectId],
                ['quarter_id', Quarter::getActiveQuarter()->id],
            ]);

            if ($assessments->count() >= $assessmentType->max_assessments) {
                return [
                    'status' => false,
                    'error' => $assessmentType->name.' has reached its maximum count for this quarter.',
                ];
            }
        }

        return [
            'status' => true,
            'assessment_type_id' => $assessmentTypeId,
        ];
    }
}
