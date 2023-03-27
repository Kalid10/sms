<?php

use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class);
uses(WithFaker::class);

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
