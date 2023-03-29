<?php

use App\Models\Level;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;

uses(DatabaseMigrations::class);

beforeEach(function () {
    // Populate levels and roles
    $this->artisan('app:create-levels');
    $this->artisan('app:create-roles');
});

it('allows users to create a batch with valid data', function () {
    // Create user and assign all roles
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    // Get the first level
    $level = Level::first();

    // Create active school year
    $schoolYear = SchoolYear::factory()->create([
        'end_date' => null,
    ]);

    $this->actingAs($user)->post('batches/create', [
        'level_id' => $level->id,
        'section' => 'A',
    ]);

    $this->assertDatabaseCount('batches', 1);
    $this->assertDatabaseHas('batches', [
        'level_id' => $level->id,
        'school_year_id' => $schoolYear->id,
    ]);
});

it('returns a validation error when the active school year is not found', function () {
    // Create user and assign all roles
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    // Get the first level
    $level = Level::find(2);

    $this->actingAs($user)->post('batches/create', [
        'level_id' => $level->id,
        'section' => 'A',
    ]);

    $this->assertArrayHasKey('school_year', Session::get('errors')->toArray());
});

it('allows users to create batches in bulk with valid data', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    SchoolYear::factory()->create([
        'end_date' => null,
    ]);

    $batchData = [
        'grade' => [
            [
                'level_id' => 1,
                'no_of_sections' => 2,
            ],
            [
                'level_id' => 2,
                'no_of_sections' => 3,
            ],
        ],
    ];

    $this->actingAs($user)->post(route('batches.create-bulk'), [
        'batches' => $batchData,
    ]);

    $this->assertDatabaseCount('batches', 5);
    $this->assertDatabaseHas('batches', [
        'level_id' => 1,
        'section' => 'A',
    ]);
    $this->assertDatabaseHas('batches', [
        'level_id' => 1,
        'section' => 'B',
    ]);
    $this->assertDatabaseHas('batches', [
        'level_id' => 2,
        'section' => 'A',
    ]);
    $this->assertDatabaseHas('batches', [
        'level_id' => 2,
        'section' => 'B',
    ]);
    $this->assertDatabaseHas('batches', [
        'level_id' => 2,
        'section' => 'C',
    ]);
});

it('returns a validation error when a level does not exist', function () {
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    $batchData = [
        'grade' => [
            [
                'level_id' => 999, // invalid level ID
                'no_of_sections' => 2,
            ],
        ],
    ];

    $this->actingAs($user)->post(route('batches.create-bulk'), [
        'batches' => $batchData,
    ]);

    $this->assertArrayHasKey('batches', Session::get('errors')->toArray());
});
