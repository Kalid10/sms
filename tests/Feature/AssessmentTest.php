<?php

use App\Models\AssessmentType;
use App\Models\Batch;
use App\Models\BatchSubject;
use App\Models\Quarter;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Carbon;

it('creates assessment if all data are valid', function () {
    // Create levels
    $this->artisan('app:create-levels');

    // Create roles
    $this->artisan('app:create-roles');

    // Get roles
    $roles = Role::all();

    // Create subjects
    $this->artisan('app:create-subjects');

    // Create a teacher
    $teacher = Teacher::factory()->create()->load('user');

    // Create quarter
    $quarter = Quarter::factory()->create([
        'end_date' => null,
    ]);

    // Create school-year, batch and subject
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);
    $batch = Batch::factory()->create(['school_year_id' => $schoolYear->id]);
    $subject = Subject::first();

    // Create batch subject
    $batchSubject = BatchSubject::factory()->create([
        'teacher_id' => $teacher->id,
    ]);

    $this->artisan('db:seed', ['--class' => 'AssessmentTypeSeeder']);

    $assessmentType = AssessmentType::first()->id;

    $requestData = [
        'batch_subject_id' => $batchSubject->id,
        'assessment_type_id' => $assessmentType,
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        'title' => 'Test Assessment',
        'description' => 'Test Assessment Description',
        'maximum_point' => 100,
        'quarter_id' => $quarter->id,
    ];

    $response = $this->actingAs($teacher->user)->post(route('teacher.assessment.create'), $requestData);

    $this->assertDatabaseHas('assessments', [
        'assessment_type_id' => $assessmentType,
        'due_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        'title' => 'Test Assessment',
        'description' => 'Test Assessment Description',
        'maximum_point' => 100,
        'quarter_id' => $quarter->id,
    ]);

    $response->assertSessionHas('success', 'Assessment created.');
});
