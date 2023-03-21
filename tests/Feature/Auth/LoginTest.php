<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

uses(DatabaseMigrations::class);

test('user can log in from an api', function () {
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

test('user can not login with incorrect password', function () {
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
