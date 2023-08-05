<?php

use App\Models\Batch;
use App\Models\HomeroomTeacher;
use App\Models\Role;
use App\Models\Teacher;
use Database\Seeders\HomeroomTeacherSeeder;
use Database\Seeders\SchoolYearSeeder;
use Illuminate\Support\Facades\Artisan;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    // populate levels and roles
    Artisan::call('app:create-levels');
    Artisan::call('app:create-roles');

    // create teacher
    $this->teacher = Teacher::factory()->create();
    // attach manage-teachers role to the teacher
    $this->teacher->user->roles()->attach(Role::where('name', 'manage-teachers')->first());

    // populate school years and batches
    $this->seed(SchoolYearSeeder::class);
    $this->batch = Batch::factory()->create();
});

it('correctly assigns a homeroom teacher', function () {
    $response = $this->actingAs($this->teacher->user)
        ->post(route('teachers.assign.homeroom'), [
            'teacher_id' => $this->teacher->id,
            'batch_id' => $this->batch->id,
            'replace' => false,
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Homeroom teacher assigned successfully.');
    expect(HomeroomTeacher::where('teacher_id', $this->teacher->id)->where('batch_id', $this->batch->id)->exists())->toBeTrue();
});

it('correctly removes a homeroom teacher', function () {
    $homeroomTeacher = HomeroomTeacher::create([
        'teacher_id' => $this->teacher->id,
        'batch_id' => $this->batch->id,
    ]);

    $response = $this->actingAs($this->teacher->user)
        ->delete('teachers/remove/homeroom/'.$homeroomTeacher->id);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Homeroom teacher removed successfully.');
    expect(HomeroomTeacher::find($homeroomTeacher->id))->toBeNull();
});

it('returns all homeroom teachers with pagination when no ids are provided', function () {
    // Seed homeroom teachers
    $this->seed(HomeroomTeacherSeeder::class);

    // Send a request to the endpoint
    $response = $this->actingAs($this->teacher->user)->get('/teachers/homerooms');

    // Assertions
    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
            //            ->has('homerooms.data', 1)
        );
});

it('returns the homeroom teacher for a given teacher id', function () {
    // Create test data
    HomeroomTeacher::factory()->create(['teacher_id' => $this->teacher->id]);

    $response = $this->actingAs($this->teacher->user)->get('/teachers/homerooms?teacher_id='.$this->teacher->id);

    // Assertions
    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
        );
});

it('returns the homeroom teacher for a given batch id', function () {
    // Create test data
    $batch = Batch::factory()->create();

    HomeroomTeacher::factory()->create(['batch_id' => $batch->id]);

    // Send a request to the endpoint
    $response = $this->actingAs($this->teacher->user)->get('/teachers/homerooms/?batch_id='.$batch->id);

    // Assertions
    // TODO: Change the component name to the correct one
    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Welcome')
        );
});
