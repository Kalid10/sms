<?php

use App\Models\BatchSession;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an absentee successfully', function () {
    $this->artisan('app:create-levels');

    $user = User::factory()->create();

    $teacher = Teacher::factory()->create()->load('user');

    // Create BatchSession with factory
    $batchSession = BatchSession::factory()->create();

    $absentees = [
        ['user_id' => $user->id, 'reason' => 'Sick'],
    ];

    $data = [
        'batch_session_id' => $batchSession->id,
        'user_type' => User::TYPE_TEACHER,
        'absentees' => $absentees,
    ];

    // Make batch session status as BatchSession::STATUS_IN_PROGRESS
    $batchSession->status = BatchSession::STATUS_IN_PROGRESS;

    $response = $this->actingAs($teacher->user)->post(route('absentees.students.add'), $data);

    $response->assertStatus(302)
        ->assertSessionHas('success', 'Absentees updated successfully.');

    $this->assertDatabaseHas('absentees', [
        'user_id' => $user->id,
        'batch_session_id' => $batchSession->id,
        'reason' => 'Sick',
    ]);
});
