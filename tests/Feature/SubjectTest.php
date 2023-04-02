<?php

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Populate roles
    Artisan::call('app:create-roles');
});

it('can create subject with manage_subjects role', function () {
    // Create a user with the manage-roles role
    $creator = User::factory()->create();
    $creator->roles()->attach(['manage-subjects']);

    // Authenticate the creator
    $this->actingAs($creator);

    // Create the request data
    $data = [
        'full_name' => 'Test Subject',
        'short_name' => 'TS',
        'category' => 'Test Category',
        'tags' => ['Test Label', 'Test Label 2'],
    ];

    // Call the create method of the controller
    $response = $this->post(route('subjects.create'), $data);

    // Check the response
    $response->assertRedirect();
    $this->assertDatabaseHas('subjects', [
        'full_name' => 'Test Subject',
        'short_name' => 'TS',
        'category' => 'Test Category',
    ]);
});

it('creates subjects can be created in bulk', function () {
    $subjects = [
        [
            'full_name' => 'Mathematics',
            'short_name' => 'MATH',
            'category' => 'Core',
            'tags' => ['algebra', 'geometry'],
        ],
        [
            'full_name' => 'Biology',
            'short_name' => 'BIO',
            'category' => 'Core',
            'tags' => ['botany', 'zoology'],
        ],
    ];

    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    $response = $this->actingAs($user)->post(route('subjects.create-bulk'), ['subjects' => $subjects]);

    $response->assertSessionHas('success', 'Subjects added successfully');

    // Verify that the subjects have been created
    foreach ($subjects as $subjectData) {
        $subject = Subject::where('full_name', $subjectData['full_name'])->first();
        expect($subject)->not()->toBeNull();
        expect($subject->short_name)->toBe($subjectData['short_name']);
        expect($subject->category)->toBe($subjectData['category']);
        expect($subject->tags)->toBe($subjectData['tags']);
    }
});

it('cannot create subject without manage_subjects role', function () {
    // Create a user without the manage-roles role
    $creator = User::factory()->create();

    // Authenticate the creator
    $this->actingAs($creator);

    // Create the request data
    $data = [
        'full_name' => 'Test Subject',
        'short_name' => 'TS',
        'category' => 'Test Category',
        'tags' => ['Test Label', 'Test Label 2'],
    ];

    // Call the create method of the controller
    $response = $this->post(route('subjects.create'), $data);

    // Check the response
    $response->assertForbidden();
    $this->assertDatabaseMissing('subjects', [
        'full_name' => 'Test Subject',
        'short_name' => 'TS',
        'category' => 'Test Category',
        'tags' => ['Test Label', 'Test Label 2'],
    ]);
});

it('can update subject with manage_subjects role', function () {
    // Create a user with the manage-roles role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-subjects']);

    // Create a subject to update without factory
    $subject = Subject::create([
        'full_name' => 'Test Subject',
        'short_name' => 'TS',
        'category' => 'Test Category',
    ]);

    // Authenticate the user
    $this->actingAs($user);

    // Create the request data
    $data = [
        'id' => $subject->id,
        'full_name' => 'New subject name',
        'short_name' => 'new_subj',
        'category' => 'New category',
    ];

    // Call the update method of the controller
    $response = $this->post(route('subjects.update'), $data);

    // Check the response
    $response->assertRedirect();
    $this->assertDatabaseHas('subjects', [
        'id' => $subject->id,
        'full_name' => 'New subject name',
        'short_name' => 'new_subj',
        'category' => 'New category',
    ]);

    // Check that the subject full name and short name are unique
    $this->assertDatabaseMissing('subjects', [
        'id' => $subject->id,
        'full_name' => $subject->full_name,
        'short_name' => $subject->short_name,
        'category' => $subject->category,
        'tags' => $subject->tags,
    ]);
});

it('cannot update subjects without manage_subjects role', function () {
    // Create a user without the manage-subjects role
    $user = User::factory()->create();

    // Create a subject to update without factory
    $subject = Subject::create([
        'full_name' => 'Test Subject',
        'short_name' => 'TS',
        'category' => 'Test Category',
        'tags' => ['Test Label', 'Test Label 2'],
    ]);

    // Authenticate the user
    $this->actingAs($user);

    // Create the request data
    $data = [
        'id' => $subject->id,
        'full_name' => 'New subject name',
        'short_name' => 'new_subj',
        'category' => 'New category',
        'tags' => ['Test Label', 'Test Label 2'],
    ];

    // Call the update method of the controller
    $response = $this->post(route('subjects.update'), $data);

    // Check the response
    $response->assertForbidden();
    $this->assertDatabaseMissing('subjects', [
        'id' => $subject->id,
        'full_name' => 'New subject name',
        'short_name' => 'new_subj',
        'category' => 'New category',
        'tags' => ['Test Label', 'Test Label 2'],
    ]);
});
