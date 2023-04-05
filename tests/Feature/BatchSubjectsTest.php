<?php

use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);
uses(DatabaseMigrations::class);

beforeEach(function () {
    // Populate levels, subjects and roles
    $this->artisan('app:create-levels');
    $this->artisan('app:create-subjects');
    $this->artisan('app:create-roles');
});

it('assigns subject to batch', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);
    $subject = Subject::first();

    $response = $this->actingAs($user)->post(route('batches.subjects.assign'), [
        'batches_subjects' => [
            [
                'batch_id' => $batch->id,
                'subject_ids' => [$subject->id],
            ],
        ],
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Batch subject added successfully.');

    $this->assertDatabaseHas('batch_subjects', [
        'batch_id' => $batch->id,
        'subject_id' => $subject->id,
    ]);
});

it('does not assign subject to batch if it already exists', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    // Create school-year, batch and subject
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);
    $subject = Subject::first();

    BatchSubject::create([
        'batch_id' => $batch->id,
        'subject_id' => $subject->id,
    ]);

    $response = $this->actingAs($user)->post(route('batches.subjects.assign'), [
        'batches_subjects' => [
            [
                'batch_id' => $batch->id,
                'subject_ids' => [$subject->id],
            ],
        ],
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['batches_subjects']);
});

it('does not assign subject to inactive batch', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    // Create school-year, batch and subject
    $schoolYear = SchoolYear::factory()->create(['end_date' => now()]);
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);
    $subject = Subject::first();

    $response = $this->actingAs($user)->post(route('batches.subjects.assign'), [
        'batches_subjects' => [
            [
                'batch_id' => $batch->id,
                'subject_ids' => [$subject->id],
            ],
        ],
    ]);

    $response->assertRedirect();
    $response->assertSessionHasErrors(['batches_subjects']);
});

it('allows teachers to be assigned to batch subjects', function () {
    // Create a teacher
    $teacher = Teacher::factory()->create();

    // Create active school year
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);

    // Create a batch with the school year
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);

    // Create a batch subject with the batch
    $batchSubject = BatchSubject::create([
        'batch_id' => $batch->id,
        'subject_id' => Subject::first()->id,
    ]);

    // Create batch subject teacher
    $batchSubjectTeacher = [
        'batch_subject_id' => $batchSubject->id,
        'teacher_id' => $teacher->id,
    ];

    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    $this->actingAs($user)->post('batches/subjects/assign/teacher', [
        'batch_subjects_teachers' => [$batchSubjectTeacher],
    ]);

    $this->assertDatabaseHas('batch_subjects', [
        'id' => $batchSubject->id,
        'teacher_id' => $teacher->id,
    ]);
});

it('does not allow teachers to be assigned to inactive batch subjects', function () {
    // Create a teacher
    $teacher = Teacher::factory()->create();

    // Create active school year
    $schoolYear = SchoolYear::factory()->create(['end_date' => Carbon::now()]);

    // Create a batch with the school year
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);

    // Create an inactive batch subject without factory
    $batchSubject = BatchSubject::create([
        'batch_id' => $batch->id,
        'subject_id' => Subject::first()->id,
    ]);

    $batchSubjectTeacher = [
        'batch_subject_id' => $batchSubject->id,
        'teacher_id' => $teacher->id,
    ];

    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    $response = $this->actingAs($user)->post('batches/subjects/assign/teacher', [
        'batch_subjects_teachers' => [$batchSubjectTeacher],
    ]);

    $response->assertSessionHasErrors(['batch_subjects_teachers']);
    $this->assertDatabaseMissing('batch_subjects', [
        'id' => $batchSubject->id,
        'teacher_id' => $teacher->id,
    ]);
});

it('does not allow teachers to be assigned to batch subjects that already have a teacher', function () {
    // Create a teacher
    $teacher1 = Teacher::factory()->create();
    $teacher2 = Teacher::factory()->create();

    // Create active school year
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);

    // Create a batch with the school year
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);

    // Create a batch subject with the batch
    $batchSubject = BatchSubject::create([
        'batch_id' => $batch->id,
        'subject_id' => Subject::first()->id,
        'teacher_id' => $teacher1->id,
    ]);

    // Create batch subject teacher
    $batchSubjectTeacher = [
        'batch_subject_id' => $batchSubject->id,
        'teacher_id' => $teacher2->id,
    ];

    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    $response = $this->actingAs($user)->post('batches/subjects/assign/teacher', [
        'batch_subjects_teachers' => [$batchSubjectTeacher],
    ]);

    $response->assertSessionHasErrors(['batch_subjects_teachers']);
    $this->assertDatabaseMissing('batch_subjects', [
        'id' => $batchSubject->id,
        'teacher_id' => $teacher2->id,
    ]);
});

it('does not allow teachers to assign teachers to batch subjects without required permissions', function () {
    // Create a teacher
    $teacher = Teacher::factory()->create();

    // Create a batch subject without factory
    $batchSubject = BatchSubject::create([
        'batch_id' => Batch::factory()->create()->id,
        'subject_id' => Subject::first()->id,
    ]);

    $batchSubjectTeacher = [
        'batch_subject_id' => $batchSubject->id,
        'teacher_id' => $teacher->id,
    ];

    // Create user and attach role
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('batches/subjects/assign/teacher', [
        'batch_subjects_teachers' => [$batchSubjectTeacher],
    ]);

    $response->assertForbidden();
});
