<?php

use App\Http\Controllers\BatchSubjectController;
use App\Http\Requests\Batches\AssignSubjectsRequest;
use App\Http\Requests\Batches\AssignSubjectTeacherRequest;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

uses(DatabaseMigrations::class);

beforeEach(function () {
    // Populate roles
    $this->artisan('app:create-levels');
    $this->artisan('app:create-roles');
    $this->artisan('app:create-levels');
});

it('can assign subjects to batches', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    // Authenticate user
    $this->actingAs($user);

    // Create a batch and subjects
    $batch = Batch::factory()->create();
    $subjects = Subject::factory()->count(3)->create();

    // Create a request with batch_id and subject_ids
    $request = new AssignSubjectsRequest([
        'batches_subjects' => [
            [
                'batch_id' => $batch->id,
                'subject_ids' => $subjects->pluck('id')->toArray(),
            ],
        ],
    ]);

    // Call the assign method on the controller
    $controller = new BatchSubjectController();
    $response = $controller->assign($request);

    // Assert that the response is a redirect with success message
    $this->assertInstanceOf(RedirectResponse::class, $response);
    $this->assertEquals('Batch subject added successfully.', Session::get('success'));

    // Assert that batch subjects were created
    foreach ($subjects as $subject) {
        $this->assertDatabaseHas('batch_subjects', [
            'batch_id' => $batch->id,
            'subject_id' => $subject->id,
        ]);
    }
});

it('can assign teacher to batch subject', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    // Authenticate user
    $this->actingAs($user);

    // Create a batch subject and a teacher
    $batchSubject = BatchSubject::factory()->create();
    $teacher = Teacher::factory()->create();

    // Create a request with batch_subject_id and teacher_id
    $request = new AssignSubjectTeacherRequest([
        'batch_subjects_teachers' => [
            [
                'batch_subject_id' => $batchSubject->id,
                'teacher_id' => $teacher->id,
            ],
        ],
    ]);

    // Call the assignTeacher method on the controller
    $controller = new BatchSubjectController();
    $response = $controller->assignTeacher($request);

    // Assert that the response is a redirect with success message
    $this->assertInstanceOf(RedirectResponse::class, $response);
    $this->assertEquals('Batch subject added successfully.', Session::get('success'));

    // Assert that the teacher was assigned to the batch subject
    $this->assertDatabaseHas('batch_subjects', [
        'id' => $batchSubject->id,
        'teacher_id' => $teacher->id,
    ]);
});
