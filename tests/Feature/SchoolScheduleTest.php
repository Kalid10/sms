<?php

use App\Models\SchoolSchedule;
use App\Models\SchoolYear;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Populate roles
    $this->artisan('app:create-roles');
});

it('creates a school schedule in the current school year', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-school-schedules']);

    // Create a new school year with end date set to null
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);

    // Authenticate user
    $this->actingAs($user);

    $startDate = Carbon::parse(now())->format('Y-m-d');
    $endDate = Carbon::parse(now()->addDays(7))->format('Y-m-d');

    // Create a new school schedule
    $schoolScheduleData = [
        'title' => 'Test School Schedule',
        'body' => 'This is a test school schedule.',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'type' => 'closed',
        'tags' => ['test'],
    ];

    $this->post('school-schedules/create', $schoolScheduleData)
        ->assertRedirect()
        ->assertSessionHas('success', $schoolScheduleData['title'].' has been added to the schedule.');

    // Check that the school schedule has been created with the correct data
    $this->assertDatabaseHas('school_schedules', [
        'title' => $schoolScheduleData['title'],
        'body' => $schoolScheduleData['body'],
        'start_date' => $schoolScheduleData['start_date'],
        'end_date' => $schoolScheduleData['end_date'],
        'type' => $schoolScheduleData['type'],
        'school_year_id' => $schoolYear->id,
    ]);
});

it('updates a school schedule successfully', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-school-schedules']);

    // Create an active school year with null end date
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);

    // Authenticate user
    $this->actingAs($user);

    // Create a school schedule to update
    $schoolSchedule = SchoolSchedule::factory()->create();

    // Update the school schedule
    $response = $this->post(route('school-schedule.update'), [
        'id' => $schoolSchedule->id,
        'title' => 'Updated School Schedule',
        'body' => 'This is an updated test school schedule.',
        'start_date' => now()->addDays(5),
        'end_date' => now()->addDays(10),
        'type' => 'half_closed',
        'school_year_id' => $schoolYear->id,
        'tags' => ['test'],
    ]);

    // Assert that the school schedule was updated in the database
    $this->assertDatabaseHas('school_schedules', [
        'id' => $schoolSchedule->id,
        'title' => 'Updated School Schedule',
        'body' => 'This is an updated test school schedule.',
        'start_date' => Carbon::now()->addDays(5)->toDateTimeString(),
        'end_date' => now()->addDays(10)->toDateTimeString(),
        'type' => 'half_closed',
        'school_year_id' => $schoolYear->id,
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Updated School Schedule has been updated.');
});

it('can delete a school schedule', function () {
    // Create user and attach role
    $user = User::factory()->create();
    $user->roles()->attach(['manage-school-schedules']);

    // Create an active school year with null end date
    $schoolYear = SchoolYear::factory()->create(['end_date' => null]);

    // Authenticate user
    $this->actingAs($user);

    // Create a new school schedule
    $schoolSchedule = SchoolSchedule::factory()->create([
        'start_date' => Carbon::now()->addMonth(),
        'school_year_id' => $schoolYear->id,
    ]);

    // Delete the school schedule
    $response = $this->delete(route('school-schedule.delete', $schoolSchedule->id));

    // Assert that the school schedule was deleted from the database
    $this->assertSoftDeleted('school_schedules', [
        'id' => $schoolSchedule->id,
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Schedule has been deleted successfully.');
});

it('cannot create a school schedule if user does not have the manage-school-schedules role', function () {
    // Create user without the manage-school-schedules role
    $user = User::factory()->create();

    // Authenticate user
    $this->actingAs($user);

    // Create a new school schedule
    $response = $this->post(route('school-schedule.create'), [
        'title' => 'Test School Schedule',
        'body' => 'This is a test school schedule.',
        'start_date' => now(),
        'end_date' => now()->addDays(7),
        'type' => 'not_closed',
        'tags' => ['test'],
    ]);

    // Assert that the user was redirected back with a forbidden message
    $response->assertForbidden();
});
