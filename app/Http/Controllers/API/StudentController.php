<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\AssessmentRequest;
use App\Http\Requests\API\StudentRequest;
use App\Http\Resources\StudentAssessmentCollection;
use App\Http\Resources\StudentCollection;
use App\Http\Resources\StudentNotesCollection;
use App\Http\Resources\StudentResource;
use App\Http\Resources\StudentSchedulesCollection;
use App\Http\Resources\StudentSubjectsCollection;
use App\Models\Student;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(StudentRequest $request, ?Student $student): StudentResource|StudentCollection
    {
        return $student->exists ?
            new StudentResource($student->load('guardian.children.user')) :
            new StudentCollection(Auth::user()->load(
                'guardian.children.user',
                'guardian.children.currentBatch.homeRoomTeacher.teacher.user'
            )->guardian->children);
    }

    public function notes(StudentRequest $request, ?Student $student): StudentNotesCollection
    {
        return $student->exists ?
            new StudentNotesCollection($student->load('studentNotes.author', 'studentNotes.student.user:id,name')->studentNotes) :
            new StudentNotesCollection(Auth::user()->load('guardian.children')->guardian->children->load('studentNotes.author', 'studentNotes.student.user:id,name')->pluck('studentNotes')->flatten());
    }

    public function subjects(StudentRequest $request, Student $student): StudentSubjectsCollection
    {
        return $student->exists ?
            new StudentSubjectsCollection($student->load('subjects')->subjects) :
            new StudentSubjectsCollection(Auth::user()->load('guardian.children')->guardian->children->load('subjects')->pluck('subjects')->flatten());
    }

    public function schedules(StudentRequest $request, Student $student): StudentSchedulesCollection
    {
        return $student->exists ?
            new StudentSchedulesCollection($student->load(
                'batches.batch.schedule.batchSubject.subject',
                'batches.batch.schedule.batchSubject.teacher.user')->batches) :
            new StudentSchedulesCollection(Auth::user()
                ->load('guardian.children')->guardian->children
                ->load(
                    'batches.batch.schedule.batchSubject.subject',
                    'batches.batch.schedule.batchSubject.teacher.user'
                )->pluck('batches')->flatten());
    }

    public function assessments(AssessmentRequest $request, ?Student $student): StudentAssessmentCollection
    {
        return $student->exists ?
            new StudentAssessmentCollection($student->load('user', 'assessments')) :
            new StudentAssessmentCollection(Auth::user()
                ->load('guardian.children.user', 'guardian.children.assessments')
                ->guardian->children
            );
    }
}
