<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Students\AssessmentRequest;
use App\Http\Requests\API\Students\GradeRequest;
use App\Http\Requests\API\Students\Request;
use App\Http\Requests\API\Students\UpdateRequest;
use App\Http\Resources\Student\AssessmentCollection;
use App\Http\Resources\Student\AssessmentResource;
use App\Http\Resources\Student\Collection;
use App\Http\Resources\Student\GradeCollection;
use App\Http\Resources\Student\GradeResource;
use App\Http\Resources\Student\NoteCollection;
use App\Http\Resources\Student\NoteResource;
use App\Http\Resources\Student\Resource;
use App\Http\Resources\Student\ScheduleCollection;
use App\Http\Resources\Student\ScheduleResource;
use App\Http\Resources\Student\SessionCollection;
use App\Http\Resources\Student\SessionResource;
use App\Http\Resources\Student\SubjectCollection;
use App\Http\Resources\Student\SubjectResource;
use App\Models\Address;
use App\Models\Student;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function update(UpdateRequest $request, Student $student): Resource
    {
        try {
            DB::beginTransaction();

            $user = $student->user;

            $user->update($request->only([
                'name', 'email', 'date_of_birth', 'phone_number', 'gender',
            ]));

            $addressData = $request->only(['sub_city', 'woreda', 'house_number']);

            if ($user->address) {
                $user->address->update($addressData);
            } else {
                $address = new Address($addressData + ['city' => 'Addis Ababa', 'country' => 'Ethiopia']);

                $user->address()->associate($address);
                $user->save();
            }

            DB::commit();

            return new Resource($student);
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
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

    public function subjects(Request $request, ?Student $student): SubjectResource|SubjectCollection
    {
        return $student->exists ?
            new SubjectResource($student->load(
                'user',
                'batches.batch.subjects.subject',
                'batches.batch.subjects.teacher.user'
            )) :
            new SubjectCollection(Auth::user()->load(
                'guardian.children.user',
                'guardian.children.batches.batch.subjects.subject',
                'guardian.children.batches.batch.subjects.teacher.user'
            )->guardian->children);
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

    public function grades(GradeRequest $request, ?Student $student): GradeResource|GradeCollection
    {
        return $student->exists ?
            new GradeResource($student->load([
                'user:id,name',
                'studentGrades' => function ($query) use ($request) {
                    $query->when($request->input('gradable_type'), function ($query) use ($request) {
                        $query->where('gradable_type', $request->input('gradable_type'));
                    });
                },
                'studentGrades.gradable:id,name',
                'studentGrades.gradeScale:id,state,description',
            ])) :
            new GradeCollection(Auth::user()
                ->load('guardian.children')->guardian->children
                ->load([
                    'user:id,name',
                    'studentGrades' => function ($query) use ($request) {
                        $query->when($request->input('gradable_type'), function ($query) use ($request) {
                            $query->where('gradable_type', $request->input('gradable_type'));
                        });
                    },
                    'studentGrades.gradable:id,name',
                    'studentGrades.gradeScale:id,state,description',
                ]));
    }
}
