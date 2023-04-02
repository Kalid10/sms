<?php

use App\Models\Admin;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

beforeEach(function () {
    // populate roles
    Artisan::call('app:create-roles');

    // Create a user with manage-users role
    $this->user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $this->user->roles()->attach(['manage-users']);
});

it('registers a guardian', function () {
    $this->actingAs($this->user);

    $payload = [
        'name' => 'Mar Smth',
        'email' => 'marsmith@example.com',
        'guardian_name' => 'John Doe',
        'guardian_email' => 'johndoe@example.com',
        'guardian_phone_number' => '0911111111',
        'username' => 'lucbrown',
        'type' => User::TYPE_STUDENT,
        'gender' => 'male',
        'guardian_gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'User created successfully.',
    ]);

    $guardianUser = User::where('email', 'johndoe@example.com')->first();
    expect($guardianUser->exists())->toBeTrue();
    expect(Guardian::where('user_id', $guardianUser->id)->exists())->toBeTrue();
});

it('registers an admin', function () {
    $this->actingAs($this->user);

    $payload = [
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.com',
        'type' => User::TYPE_ADMIN,
        'position' => 'Manager',
        'gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'User created successfully.',
    ]);

    $adminUser = User::where('email', 'janedoe@example.com')->first();
    expect($adminUser->exists())->toBeTrue();
    expect(Admin::where('user_id', $adminUser->id)->exists())->toBeTrue();
});

it('registers a teacher', function () {
    $this->actingAs($this->user);

    $payload = [
        'name' => 'Mark Smith',
        'email' => 'marksmith@example.com',
        'type' => User::TYPE_TEACHER,
        'gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'User created successfully.',
    ]);

    $teacherUser = User::where('email', 'marksmith@example.com')->first();
    expect($teacherUser->exists())->toBeTrue();
    expect(Teacher::where('user_id', $teacherUser->id)->exists())->toBeTrue();
});

it('registers a student', function () {
    $this->actingAs($this->user);

    $guardian = Guardian::factory()->create();

    Log::info($guardian->user->name);
    $payload = [
        'name' => 'Lucy Brown',
        'email' => 'lucybrown@example.com',
        'type' => User::TYPE_STUDENT,
        'gender' => 'male',
        'guardian_name' => 'John Doe',
        'guardian_email' => 'johndoe@example.com',
        'guardian_phone_number' => '0911111111',
        'username' => 'lucybrown',
        'guardian_gender' => 'male',

    ];

    $response = $this->postJson('/register', $payload);

    Log::info($response->getContent());
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'User created successfully.',
    ]);

    $studentUser = User::where('email', 'lucybrown@example.com')->first();
    expect($studentUser->exists())->toBeTrue();
    expect(Student::where('user_id', $studentUser->id)->exists())->toBeTrue();
});

it('fails with unknown user type', function () {
    $this->actingAs($this->user);

    $payload = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'type' => 'unknown',
        'gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'The selected type is invalid.',
    ]);
});

it('requires authentication to register a user', function () {
    $payload = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'type' => User::TYPE_GUARDIAN,
        'gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(401); // Unauthorized
});

it('requires manage-users role to register a user', function () {
    // Create a user without manage-users role
    $userWithoutRole = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($userWithoutRole);

    $payload = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'type' => User::TYPE_GUARDIAN,
        'gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(403); // Forbidden
    $response->assertJson([
        'message' => 'You are not authorized to perform this action!',
    ]);
});

it('checks for specific manage-X role when registering a user', function () {
    // Create a user with only manage-teachers role
    $userWithSpecificRole = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $userWithSpecificRole->roles()->attach(['manage-teachers']);

    $this->actingAs($userWithSpecificRole);

    $payload = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'type' => User::TYPE_GUARDIAN,
        'gender' => 'male',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(403); // Forbidden
    $response->assertJson([
        'message' => 'You are not authorized to perform this action!',
    ]);
});
