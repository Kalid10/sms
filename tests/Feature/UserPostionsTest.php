<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    // Populate roles
    $this->artisan('app:create-roles');
});
it('creates a new user position with valid data', function () {
    // Get all roles
    $roles = Role::all();

    // Create a user
    $user = User::factory()->create();
    $user->roles()->attach($roles->pluck('name'));

    // Prepare valid request data
    $requestData = [
        'name' => $this->faker->unique()->jobTitle,
        'description' => $this->faker->sentence,
        'role_names' => $roles->pluck('name')->toArray(),
    ];

    // Send request to the endpoint
    $response = $this->actingAs($user)->post(route('user-positions.create'), $requestData);

    // Assert the new user position was created
    $this->assertDatabaseHas('user_positions', [
        'name' => $requestData['name'],
        'description' => $requestData['description'],
    ]);

    // Assert the response contains a success message
    $response->assertRedirect()->assertSessionHas('success', $requestData['name'].' added successfully');

    // Assert the response contains a success message
    $response->assertRedirect()->assertSessionHas('success', $requestData['name'].' added successfully');
});
