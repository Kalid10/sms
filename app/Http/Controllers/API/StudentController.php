<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Students\AssessmentRequest;
use App\Http\Requests\API\Students\GetRequest;
use App\Http\Requests\API\Students\UpdateRequest;
use App\Http\Resources\Student\AssessmentCollection;
use App\Http\Resources\Student\AssessmentResource;
use App\Http\Resources\Student\Collection;
use App\Http\Resources\Student\NoteCollection;
use App\Http\Resources\Student\Resource;
use App\Http\Resources\Student\ScheduleCollection;
use App\Http\Resources\Student\SubjectCollection;
use App\Models\Student;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(GetRequest $request, ?Student $student): Resource|Collection
    {
        return $student->exists ?
            new Collection($student->load('guardian.children.user')) :
            new Collection(Auth::user()->load(
                'guardian.children.user',
                'guardian.children.currentBatch.homeRoomTeacher.teacher.user'
            )->guardian->children);
    }

    public function update(UpdateRequest $request, Student $student): Resource
    {
        $user = $student->user;

        $user->update($request->validated());

        return new Resource($student);
    }

    public function notes(GetRequest $request, ?Student $student): NoteCollection
    {
        return $student->exists ?
            new NoteCollection($student->load('studentNotes.author', 'studentNotes.student.user:id,name')->studentNotes) :
            new NoteCollection(Auth::user()->load('guardian.children')->guardian->children->load('studentNotes.author', 'studentNotes.student.user:id,name')->pluck('studentNotes')->flatten());
    }

    public function subjects(GetRequest $request, ?Student $student): SubjectCollection
    {
        return $student->exists ?
            new SubjectCollection($student->load('subjects')->subjects) :
            new SubjectCollection(Auth::user()->load('guardian.children')->guardian->children->load('subjects')->pluck('subjects')->flatten());
    }

    public function schedules(GetRequest $request, ?Student $student): ScheduleCollection
    {
        return $student->exists ?
            new ScheduleCollection($student->load(
                'batches.batch.schedule.batchSubject.subject',
                'batches.batch.schedule.batchSubject.teacher.user')->batches) :
            new ScheduleCollection(Auth::user()
                ->load('guardian.children')->guardian->children
                ->load(
                    'batches.batch.schedule.batchSubject.subject',
                    'batches.batch.schedule.batchSubject.teacher.user'
                )->pluck('batches')->flatten());
    }

    public function assessments(AssessmentRequest $request, ?Student $student): AssessmentResource|AssessmentCollection
    {
        return $student->exists ?
            new AssessmentResource($student->load(
                'user',
                'currentBatch.subjects.assessments.assessmentType',
                'currentBatch.subjects.assessments.batchSubject.subject')) :
            new AssessmentCollection(Auth::user()
                ->load(
                    'guardian.children.user',
                    'guardian.children.currentBatch.subjects.assessments.assessmentType',
                    'guardian.children.currentBatch.subjects.assessments.batchSubject.subject',
                )
                ->guardian->children
            );
    }
}
