<?php

use App\Models\Batch;
use App\Models\HomeroomTeacher;
use App\Models\Role;
use App\Models\Teacher;
use Database\Seeders\SchoolYearSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

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

test('assignHomeroomTeacher method works correctly', function () {
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

test('removeHomeroomTeacher method works correctly', function () {
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
