<?php

use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->artisan('app:create-roles');
});

it('creates a new school year with semesters', function () {
    // Set up request data
    $requestData = [
        'start_date' => now()->addDays(1)->format('Y-m-d h:i:s'),
        'number_of_semesters' => 2,
        'name' => '2023/2024 Academic Year',
    ];

    // Create user with roles attached
    $user = User::factory()->create();
    $user->roles()->attach(['manage-school-years']);

    // Send POST request to the create method of SchoolYearController
    $response = $this->actingAs($user)->post('school-year/create', $requestData);

    // Check if the request was successful
    $response->assertRedirect();
    $response->assertSessionHas('success', 'School year created successfully with.');

    // Check if the school year and semesters were created in the database
    $schoolYear = SchoolYear::first();
    expect($schoolYear->start_date)->toBe($requestData['start_date']);
    expect($schoolYear->end_date)->toBe(null);
    expect($schoolYear->name)->toBe($requestData['name']);

    // Check number of semesters
    $semesters = Semester::all();
    expect($semesters->count())->toBe($requestData['number_of_semesters']);

    // Check the semesters
    for ($i = 1; $i <= $requestData['number_of_semesters']; $i++) {
        $semester = $semesters->where('name', "Semester {$i}")->first();

        expect($semester->school_year_id)->toBe($schoolYear->id);
        expect($semester->start_date)->toBe($i === 1 ? $requestData['start_date'] : null);
        expect($semester->end_date)->toBe(null);
        expect($semester->status)->toBe($i === 1 ? Semester::STATUS_ACTIVE : Semester::STATUS_UPCOMING);
    }
});

it('does not allow creating a new school year while another one is ongoing', function () {
    // Create an ongoing school year
    SchoolYear::factory()->create(['end_date' => null]);

    // Set up request data
    $requestData = [
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'number_of_semesters' => 2,
        'name' => '2023/2024 Academic Year',
    ];

    // Create user with roles attached
    $user = User::factory()->create();
    $user->roles()->attach(['manage-school-years']);

    // Send POST request to the create method of SchoolYearController
    $response = $this->actingAs($user)->post('school-year/create', $requestData);

    // Check if the request was unsuccessful due to ongoing school year
    $response->assertRedirect();
    $response->assertSessionHas('error', 'The current academic year is ongoing. You cannot start a new academic year without ending the current one.');

    // Assert that no new school year was created
    $schoolYears = SchoolYear::all();
    expect($schoolYears->count())->toBe(1);
});
