<?php

use App\Models\Batch;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\BatchScheduleSeeder;
use Database\Seeders\BatchSessionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('fetches batch sessions', function () {
    // Create levels
    $this->artisan('app:create-levels');

    // Create user and assign all roles
    $user = User::factory()->create();
    $user->roles()->attach(Role::all());

    // Acting as user
    $this->actingAs($user);

    $batch = Batch::factory()->create();

    $batchSchedule = $this->seed(BatchScheduleSeeder::class, $batch);

    $this->seed(BatchSessionSeeder::class, $batchSchedule);

    $response = $this->get(route('session.batch', ['batch_id' => 1]));

    $response->assertStatus(200);

    // ToDo: Add additional assertions
});
