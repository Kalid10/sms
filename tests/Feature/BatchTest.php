<?php

use App\Models\Level;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

uses(DatabaseMigrations::class, RefreshDatabase::class);

beforeEach(function () {
    // Populate levels and roles
    $this->artisan('app:create-levels');
    $this->artisan('app:create-roles');

    // Create active school year
    $this->schoolYear = SchoolYear::factory()->create([
        'end_date' => null,
    ]);
});

it('allows users to create a batch with valid data', function () {
    // Create user and assign all roles
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    // Get the first level
    $level = Level::first();

    $this->actingAs($user)->post('batches/create', [
        'level_id' => $level->id,
        'section' => 'A',
        'min_students' => 5,
        'max_students' => 10,
    ]);

    $this->assertDatabaseCount('batches', 1);
    $this->assertDatabaseHas('batches', [
        'level_id' => $level->id,
        'school_year_id' => $this->schoolYear->id,
    ]);
});

it('allows users to create batches in bulk with valid data', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    $batchData = [
        [
            'level_id' => 1,
            'no_of_sections' => 2,
            'min_students' => 5,
            'max_students' => 10,
        ],
        [
            'level_id' => 2,
            'no_of_sections' => 3,
        ],
    ];

    $this->actingAs($user)->post('batches/create-bulk', [
        'batches' => $batchData,
    ]);

    $this->assertDatabaseCount('batches', 5);
    $this->assertDatabaseHas('batches', [
        'level_id' => 1,
        'section' => 'A',
        'min_students' => 5,
        'max_students' => 10,
    ]);
});

it('returns a validation error when the active school year is not found', function () {
    // Create user and assign all roles
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    // Get the first level
    $level = Level::find(2);

    // Delete the active school year
    $this->schoolYear->delete();

    $this->actingAs($user)->post('batches/create', [
        'level_id' => $level->id,
        'section' => 'A',
    ]);

    $this->assertArrayHasKey('school_year', Session::get('errors')->toArray());
});
