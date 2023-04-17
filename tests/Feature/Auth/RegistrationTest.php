<?php

use App\Models\Admin;
use App\Models\Level;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    // populate roles
    Artisan::call('app:create-roles');
    Artisan::call('app:create-levels');

    // Create a user with manage-users role
    $this->user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);
    $this->user->roles()->attach(['manage-users']);
});

it('registers an admin', function () {
    $this->actingAs($this->user);

    $payload = [
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.com',
        'type' => User::TYPE_ADMIN,
        'position' => 'Manager',
        'gender' => 'male',
        'date_of_birth' => '1990-01-01',
        'phone_number' => '0911223344',
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
        'date_of_birth' => '1990-01-01',
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

    // Get first level
    $level = Level::first();

    // Add school year and batch seeder
    $this->seed('SchoolYearSeeder');
    $this->seed('BatchSeeder');

    $payload = [
        'name' => 'Lucy Brown',
        'email' => 'lucybrown@example.com',
        'type' => User::TYPE_STUDENT,
        'gender' => 'male',
        'date_of_birth' => '1990-01-01',
        'guardian_name' => 'John Doe',
        'guardian_email' => 'johndoe@example.com',
        'guardian_phone_number' => '0911111111',
        'username' => 'lucybrown',
        'guardian_gender' => 'male',
        'level_id' => $level->id,
        'guardian_relation' => 'father',
    ];

    // Register student
    $response = $this->post('/register', $payload);

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
        'date_of_birth' => '1990-01-01',
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
        'date_of_birth' => '1990-01-01',
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
        'type' => User::TYPE_TEACHER,
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
    $userWithSpecificRole->roles()->attach(['manage-guardians']);

    $this->actingAs($userWithSpecificRole);

    $payload = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'type' => User::TYPE_TEACHER,
        'gender' => 'male',
        'date_of_birth' => '1990-01-01',
    ];

    $response = $this->postJson('/register', $payload);

    $response->assertStatus(403); // Forbidden
    $response->assertJson([
        'message' => 'You are not authorized to perform this action!',
    ]);
});
