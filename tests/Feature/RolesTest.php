<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    // Setup the test environment
    protected function setUp(): void
    {
        parent::setUp();

        // Populate roles
        Artisan::call('app:create-roles');
    }

    public function test_assign_roles_with_manage_roles()
    {
        // Create a user with the manage-roles role
        $assigner = User::factory()->create();
        $assigner->roles()->attach(['manage-roles']);

        // Authenticate the assigner
        $this->actingAs($assigner);

        // Create another user to assign roles to
        $user = User::factory()->create();

        // Get the roles
        $roles = Role::all();

        // Generate some random role names
        $roleNames = $roles->pluck('name')->random(2);

        // Create the request data
        $data = [
            'user_id' => $user->id,
            'roles' => $roleNames->toArray(),
        ];

        // Call the assign method of the controller
        $response = $this->post(route('roles.assign'), $data);

        // Check the response
        $response->assertRedirect();
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_name' => $roleNames[0],
        ]);
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_name' => $roleNames[1],
        ]);
    }

    public function test_assign_roles_without_manage_roles()
    {
        // Create a user who will be assigned roles
        $user = User::factory()->create();

        // Create a user who will try to assign roles
        $assigner = User::factory()->create();

        // Give the assigner some roles but not the manage-roles role
        $assigner->roles()->attach(['manage-levels', 'manage-subjects']);

        // Authenticate the assigner
        $this->actingAs($assigner);

        // Get the roles
        $roles = Role::all();

        // Generate some random role names
        $roleNames = $roles->pluck('name')->random(2);

        // Create the request data
        $data = [
            'user_id' => $user->id,
            'roles' => $roleNames,
        ];

        // Call the assign method of the controller
        $response = $this->post(route('roles.assign'), $data);

        // Check the response
        $response->assertForbidden();
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user->id,
            'role_name' => $roleNames[0],
        ]);
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user->id,
            'role_name' => $roleNames[1],
        ]);
    }
}
