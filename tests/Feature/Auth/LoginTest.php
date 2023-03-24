<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

uses(DatabaseMigrations::class);

it('allows a user to log in via the API route', function () {
    // Create a user
    $password = 'secret';
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    // Make a request to log in
    $response = $this->postJson('/login', [
        'emailOrPhone' => $user->email,
        'password' => $password,
    ]);

    // Assert that the response is successful and contains a token
    $response->assertSuccessful();
    $response->assertJsonStructure([
        'message',
        'user',
        'token',
    ]);
    $response->assertJson([
        'message' => 'You have successfully logged in.',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ]);
    $token = $response->json('token');
    $this->assertNotNull($token);
});

it('prevents a user from logging in with an incorrect password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret'),
    ]);

    $response = $this->postJson('/login', [
        'emailOrPhone' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'These credentials do not match our records.',
    ]);

    $this->assertGuest();
});
