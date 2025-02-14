<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Populate roles
    Artisan::call('app:create-roles');
});

it('allows assigning roles with manage roles permission', function () {
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
});

it('prevents assigning roles without manage roles permission', function () {
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
});

it('allows removing roles with manage roles permission', function () {
    // Create a user with the manage-roles role
    $remover = User::factory()->create();
    $remover->roles()->attach(['manage-roles']);

    // Create a user to remove roles from
    $user = User::factory()->create();

    // Assign roles to the user
    $user->roles()->attach(['manage-levels', 'manage-subjects']);

    // Authenticate the remover
    $this->actingAs($remover);

    // Create the request data
    $data = [
        'user_id' => $user->id,
        'roles' => ['manage-levels'],
    ];

    // Call the remove method of the controller
    $response = $this->post(route('roles.remove'), $data);

    // Check the response
    $response->assertRedirect();
    $this->assertDatabaseHas('user_roles', [
        'user_id' => $user->id,
        'role_name' => 'manage-levels',
    ]);
    $this->assertDatabaseHas('user_roles', [
        'user_id' => $user->id,
        'role_name' => 'manage-subjects',
    ]);
});

it('prevents removing roles without manage roles permission', function () {
    // Create a user to remove roles from
    $user = User::factory()->create();
    // Create another user to remove roles from
    $otherUser = User::factory()->create();

    // Assign roles to the other user
    $otherUser->roles()->attach(['manage-levels', 'manage-subjects']);

    // Authenticate the user
    $this->actingAs($user);

    // Create the request data
    $data = [
        'user_id' => $otherUser->id,
        'roles' => ['manage-levels'],
    ];

    // Call the remove method of the controller
    $response = $this->post(route('roles.remove'), $data);

    // Check the response
    $response->assertForbidden();
    $this->assertDatabaseHas('user_roles', [
        'user_id' => $otherUser->id,
        'role_name' => 'manage-levels',
    ]);
    $this->assertDatabaseHas('user_roles', [
        'user_id' => $otherUser->id,
        'role_name' => 'manage-subjects',
    ]);
});
