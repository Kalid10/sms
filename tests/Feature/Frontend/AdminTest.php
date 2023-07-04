<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

it('renders the admin index page', function () {
    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    // When the user visits the admin page
    $response = $this->actingAs($admin)->get('/admin');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/Index')
            ->has('teachers_count')
            ->has('students_count')
            ->has('subjects_count')
            ->has('admins_count')
            ->has('user_roles')
            ->has('admins')
            ->has('levels')
            ->has('school_year')
            ->has('announcements');
    });
});

it('renders the admin schedule page', function () {
    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    // When the user visits the admin page
    $response = $this->actingAs($admin)->get('/admin/schedules');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/Schedules/Index')
            ->has('school_schedule');
    });
});

it('renders the levels page', function () {
    // Create roles
    $this->artisan('app:create-roles');

    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    $admin->roles()->attach(['manage-levels']);

    $response = $this->actingAs($admin)->get('admin/levels');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/Levels/Index')
            ->has('levels');
    });
});
//
//it('renders the teachers index page', function () {
//    // Create admin user
//    $admin = User::factory()->create([
//        'type' => 'admin',
//    ]);
//
//    // Create roles
//    $this->artisan('app:create-roles');
//    $admin->roles()->attach(['manage-teachers']);
//
//    // When the user visits the teachers page
//    $response = $this->actingAs($admin)->get('admin/teachers');
//
//    // Assert successful response and correct data
//    $response->assertInertia(function (AssertableInertia $page) {
//        $page->component('Admin/Teachers/Index')
//            ->has('teachers')
//            ->has('subjects')
//            ->has('batches');
//    });
//});

it('renders the roles index page', function () {
    // Create roles
    $this->artisan('app:create-roles');

    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    $admin->roles()->attach(['manage-roles']);

    // When the user visits the roles page
    $response = $this->actingAs($admin)->get('roles/users');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/Roles/Index')
            ->has('users');
    });
});

it('renders the roles detail page', function () {
    // Create roles
    $this->artisan('app:create-roles');

    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    $admin->roles()->attach(['manage-roles']);

    // When the user visits the roles page
    $response = $this->actingAs($admin)->get('roles/');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/Roles/Detail')
            ->has('roles');
    });
});

it('renders the assessment type index page', function () {
    // Create roles
    $this->artisan('app:create-roles');

    // Create admin user
    $admin = User::factory()->create([
        'type' => 'admin',
    ]);

    $admin->roles()->attach(['manage-assessment-types']);

    // When the user visits the roles page
    $response = $this->actingAs($admin)->get('assessments/type/');

    // Assert successful response and correct data
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Admin/Assessments/AssessmentTypes/Index')
            ->has('assessment_types')
            ->has('level_categories');
    });
});
