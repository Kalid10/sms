<?php

use App\Http\Requests\Semesters\CreateRequest;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;

uses(DatabaseMigrations::class);

beforeEach(function () {
    // Populate roles
    $this->artisan('app:create-roles');

    // Create user and attach roles
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    // Use this user for the rest of the test
    $this->actingAs($user);
});

it('creates semesters with valid data', function () {
    SchoolYear::factory()->create([
        'end_date' => null,
    ]);

    $start1 = now()->addDays(7);
    $end1 = now()->addDays(14);
    $start2 = now()->addDays(21);
    $end2 = now()->addDays(28);

    $data = [
        [
            'name' => 'First Semester',
            'start_date' => $start1,
            'end_date' => $end1,
        ],
        [
            'name' => 'Second Semester',
            'start_date' => $start2,
            'end_date' => $end2,
        ],
    ];

    $request = new CreateRequest();
    $request->merge(['semesters' => $data]);

    $response = $this->post('/semesters/create', $request->all());

    $response->assertRedirect();
    $response->assertSessionHas('success');

    DB::table('semesters')->where('name', 'First Semester')->delete();
    DB::table('semesters')->where('name', 'Second Semester')->delete();
});

it('does not create semesters when there is no ongoing academic year', function () {
    $data = [
        [
            'name' => 'First Semester',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
        ],
    ];

    $request = new CreateRequest();
    $request->merge(['semesters' => $data]);

    $response = $this->post('/semesters/create', $request->all());

    $response->assertSessionHasErrors();
});

it('does not create semesters when there is a conflict with an existing semester', function () {
    $semester = Semester::factory()->create(['name' => 'First Semester']);

    $data = [
        [
            'name' => 'First Semester',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
        ],
    ];

    $request = new CreateRequest();
    $request->merge(['semesters' => $data]);

    $response = $this->post('/semesters/create', $request->all());

    $response->assertSessionHasErrors();

    $semester->delete();
});

it('does not create semesters when there is validation error', function () {
    $data = [
        [
            'name' => 'First Semester',
            'start_date' => now()->addDays(14),
            'end_date' => now()->addDays(7),
        ],
    ];

    $request = new CreateRequest();
    $request->merge(['semesters' => $data]);

    $response = $this->post('/semesters/create', $request->all());

    $response->assertSessionHasErrors();
});

it('updates a semester successfully', function () {
    $semester = Semester::factory()->create();

    $newData = [
        'name' => 'Second Semester',
        'start_date' => now()->addDays(1)->format('Y-m-d h:i:s'),
        'end_date' => now()->addMonths(4)->format('Y-m-d h:i:s'),
    ];

    $response = $this->post(route('semesters.update'), array_merge($newData, ['id' => $semester->id]));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Semester updated successfully.');

    $updatedSemester = Semester::find($semester->id);

    $this->assertEquals($newData['name'], $updatedSemester->name);
    $this->assertEquals($newData['start_date'], $updatedSemester->start_date);
    $this->assertEquals($newData['end_date'], $updatedSemester->end_date);
});

it('cannot update a semester with an incorrect ID', function () {
    // Create a semester
    $semester = Semester::factory()->create();

    // Attempt to update the semester with an incorrect ID
    $response = $this->post('semester/update', [
        'id' => $semester->id + 1, // Use an incorrect ID
        'name' => 'Updated Semester Name',
        'start_date' => now()->addWeek()->format('Y-m-d'),
        'end_date' => now()->addWeeks(15)->format('Y-m-d'),
    ]);

    // Assert that the response has a 404 status code
    $response->assertStatus(404);

    // Assert that the semester was not updated
    $this->assertDatabaseHas('semesters', [
        'id' => $semester->id,
        'name' => $semester->name,
        'start_date' => $semester->start_date,
        'end_date' => $semester->end_date,
    ]);
});

it('can delete an upcoming semester', function () {
    $semester = Semester::factory()->create([
        'status' => Semester::STATUS_UPCOMING,
    ]);

    $response = $this->delete('semesters/delete/'.$semester->id);

    $response->assertSessionHas('success', 'Semester deleted successfully');
    $this->assertSoftDeleted('semesters', [
        'id' => $semester->id,
    ]);
});

it('cannot delete a completed semester', function () {
    $semester = Semester::factory()->create([
        'status' => Semester::STATUS_COMPLETED,
    ]);

    $response = $this->delete(route('semesters.delete', $semester->id));

    $response->assertSessionHas('error', 'Can only delete upcoming semesters.');
    $this->assertDatabaseHas('semesters', [
        'id' => $semester->id,
        'status' => Semester::STATUS_COMPLETED,
    ]);
});

it('cannot delete a nonexistent semester', function () {
    $response = $this->delete(route('semesters.delete', 999));
    $response->assertRedirect();
    $response->assertSessionHas('error', 'Semester not found.');
});
