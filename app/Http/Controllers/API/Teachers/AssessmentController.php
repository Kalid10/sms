<?php

namespace App\Http\Controllers\API\Teachers;

use App\Http\Requests\API\Teachers\Assessments\AssessmentRequest;
use App\Http\Requests\API\Teachers\Assessments\MarkAssessmentRequest;
use App\Http\Requests\API\Teachers\Assessments\UpdateAssessmentStatusRequest;
use App\Http\Resources\Teachers\AssessmentCollection;
use App\Http\Resources\Teachers\AssessmentResource;
use App\Http\Resources\Teachers\StudentAssessmentCollection;
use App\Jobs\InsertStudentsAssessmentsJob;
use App\Models\Assessment;
use App\Models\StudentAssessment;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;

class AssessmentController extends Controller
{
    /**
     * @return AssessmentResource|AssessmentCollection
     *
     * Return the authenticated teacher's assessments.
     */
    public function index(AssessmentRequest $request, ?Assessment $assessment): AssessmentCollection|AssessmentResource
    {
        if ($assessment->exists) {

            $assessment->load([
                'quarter.semester',
                'batchSubject.batch.schoolYear',
                'batchSubject.batch.level.levelCategory',
                'batchSubject.subject',
            ]);

            return new AssessmentResource($assessment->load('batchSubject'));

        }

        $teacher = auth()->user()->load('teacher')->teacher;

        $assessments = $teacher
            ->load(
                'assessments.quarter.semester',
                'assessments.batchSubject.batch.schoolYear',
                'assessments.batchSubject.batch.level.levelCategory',
                'assessments.batchSubject.subject'
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
     * @return StudentAssessmentCollection
     *
     * Return the students of the given assessment.
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
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     *
     * Update the assessment's status.
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
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     *
     * Insert the students' assessments.
     */
    public function mark(MarkAssessmentRequest $request, Assessment $assessment): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        InsertStudentsAssessmentsJob::dispatch($request->validated('points'), $assessment);

        return response([
            'message' => 'Your students\'s assessments are being inserted. We will notify you when it\'s done.',
        ], 200);
    }

    /**
     * @return void
     *
     * Filter the active assessments.
     */
    private function filterActiveAssessments($query): void
    {
        $query->whereHas('quarter', function ($query) {
            $query->whereNull('end_date');
        });
    }
}
