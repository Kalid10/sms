<?php

namespace Tests\Feature;

use App\Models\Role;
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
}
