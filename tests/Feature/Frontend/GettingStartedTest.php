<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('renders the getting started page', function () {
    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    // When the user visits the getting started page
    $response = $this->actingAs($admin)->get('/getting-started');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/GettingStarted/Index')
            ->has('step');
    });
});

it('renders class schedule step', function () {
    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    // When the user visits the getting started page
    $response = $this->actingAs($admin)->get('/getting-started/class-schedule');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/GettingStarted/ClassSchedule')
            ->has('school_schedule');
    });
});
