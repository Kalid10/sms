<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    // Setup the test environment
    protected function setUp(): void
    {
        parent::setUp();

        // Populate roles
        Artisan::call('app:create-roles');
    }

    public function test_create_subject_with_manage_subjects_role()
    {
        // Create a user with the manage-roles role
        $creator = User::factory()->create();
        $creator->roles()->attach(['manage-subjects']);

        // Authenticate the creator
        $this->actingAs($creator);

        // Create the request data
        $data = [
            'full_name' => 'Test Subject',
            'short_name' => 'TS',
        ];

        // Call the create method of the controller
        $response = $this->post(route('subjects.create'), $data);

        // Check the response
        $response->assertRedirect();
        $this->assertDatabaseHas('subjects', [
            'full_name' => 'Test Subject',
            'short_name' => 'TS',
        ]);
    }

    public function test_create_subject_without_manage_subjects_role()
    {
        // Create a user without the manage-roles role
        $creator = User::factory()->create();

        // Authenticate the creator
        $this->actingAs($creator);

        // Create the request data
        $data = [
            'full_name' => 'Test Subject',
            'short_name' => 'TS',
        ];

        // Call the create method of the controller
        $response = $this->post(route('subjects.create'), $data);

        // Check the response
        $response->assertForbidden();
        $this->assertDatabaseMissing('subjects', [
            'full_name' => 'Test Subject',
            'short_name' => 'TS',
        ]);
    }

    public function test_update_subject_with_manage_subjects_role()
    {
        // Create a user with the manage-roles role
        $user = User::factory()->create();
        $user->roles()->attach(['manage-subjects']);

        // Create a subject to update without factory
        // TODO: Change to factory
        $subject = Subject::create([
            'full_name' => 'Test Subject',
            'short_name' => 'TS',
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Create the request data
        $data = [
            'id' => $subject->id,
            'full_name' => 'New subject name',
            'short_name' => 'new_subj',
        ];

        // Call the update method of the controller
        $response = $this->post(route('subjects.update'), $data);

        // Check the response
        $response->assertRedirect();
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'full_name' => 'New subject name',
            'short_name' => 'new_subj',
        ]);

        // Check that the subject full name and short name are unique
        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
            'full_name' => $subject->full_name,
            'short_name' => $subject->short_name,
        ]);
    }

    public function test_update_subject_without_manage_subjects_role()
    {
        // Create a user without the manage-subjects role
        $user = User::factory()->create();

        // Create a subject to update without factory
        // TODO: Change to factory
        $subject = Subject::create([
            'full_name' => 'Test Subject',
            'short_name' => 'TS',
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // Create the request data
        $data = [
            'id' => $subject->id,
            'full_name' => 'New subject name',
            'short_name' => 'new_subj',
        ];

        // Call the update method of the controller
        $response = $this->post(route('subjects.update'), $data);

        // Check the response
        $response->assertForbidden();
        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
            'full_name' => 'New subject name',
            'short_name' => 'new_subj',
        ]);
    }
}
