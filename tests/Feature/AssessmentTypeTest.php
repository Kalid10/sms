<?php

use App\Models\Admin;
use App\Models\AssessmentType;
use App\Models\LevelCategory;
use App\Models\SchoolYear;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('app:create-roles');

    LevelCategory::factory()->count(3)->create();
    SchoolYear::factory()->create([
        'end_date' => null,
    ]);

    // Authenticate a user
    $this->admin = Admin::factory()->create();
    $this->admin->user->roles()->attach(['manage-assessment-types']);
    $this->actingAs($this->admin->user);
});

it('it lists assessment types', function () {
    // Create assessment types using the seeder
    $this->artisan('db:seed', ['--class' => 'AssessmentTypeSeeder']);

    $response = $this->get(route('assessments.type.index'));

    $response->assertStatus(200);
});

it('it creates a new assessment type', function () {
    $levelCategory = LevelCategory::first();
    $data = [
        'name' => 'Test Assessment Type',
        'percentage' => 50,
        'customizable' => false,
        'min_assessments' => null,
        'max_assessments' => null,
        'level_category_id' => [$levelCategory->id],
    ];

    $response = $this->post(route('assessments.type.create'), $data);

    // Assert that the announcement was created in the database
    $response->assertRedirect();
    $this->assertDatabaseHas('assessment_types', [
        'name' => 'Test Assessment Type',
        'percentage' => 50,
        'customizable' => false,
        'min_assessments' => null,
        'max_assessments' => null,
        'level_category_id' => [$levelCategory->id],
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Assessment type created successfully.');
});

it('it updates an existing assessment type', function () {
    $this->artisan('db:seed', ['--class' => 'AssessmentTypeSeeder']);

    $assessmentType = AssessmentType::first();

    $data = [
        'id' => $assessmentType->id,
        'name' => 'Updated Assessment Type',
        'percentage' => 60,
        'customizable' => false,
        'min_assessments' => null,
        'max_assessments' => null,
        'level_category_id' => $assessmentType->level_category_id,
    ];

    $response = $this->post(route('assessments.type.update', $assessmentType->id), $data);

    $this->assertDatabaseHas('assessment_types', [
        'id' => $assessmentType->id,
        'name' => 'Updated Assessment Type',
        'percentage' => 60,
        'customizable' => false,
        'min_assessments' => null,
        'max_assessments' => null,
        'level_category_id' => $assessmentType->level_category_id,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHas('success', 'Assessment type updated successfully.');
    $this->assertEquals('Updated Assessment Type', $assessmentType->fresh()->name);
});

it('it deletes an existing assessment type', function () {
    $this->artisan('db:seed', ['--class' => 'AssessmentTypeSeeder']);

    $assessmentType = AssessmentType::first();

    $response = $this->delete(route('assessments.type.destroy', $assessmentType->id));

    $response->assertStatus(302);
    $response->assertSessionHas('success', 'Assessment type deleted successfully.');
});
