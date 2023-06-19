<?php

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\SchoolYear;

beforeEach(function () {
    $this->artisan('app:create-roles');

    // Create a test admin user
    $this->admin = Admin::factory()->create();

    $this->admin->user->roles()->attach(['manage-announcements']);

    // Use this user for this test
    $this->actingAs($this->admin->user);
});

it('can create a new announcement', function () {
    SchoolYear::factory()->create([
        'end_date' => null,
    ]);

    // Create a new announcement
    $response = $this->post(route('announcements.create'), [
        'title' => 'New announcement',
        'body' => 'This is a test announcement',
        'expires_on' => now()->addDays(7)->toDateString(),
        'target_group' => ['all'],
    ]);

    // Assert that the announcement was created in the database
    $response->assertRedirect();
    $this->assertDatabaseHas('announcements', [
        'title' => 'New announcement',
        'body' => 'This is a test announcement',
        'author_id' => $this->admin->id,
        'expires_on' => now()->addDays(7)->toDateString(),
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Announcement created successfully');
});

it('can list all announcements', function () {
    // Create some test announcements
    Announcement::factory()->count(3)->create();

    // Get the announcements index page
    $response = $this->get(route('announcements.index'));

    // Check no of announcements in the database
    $this->assertEquals(3, Announcement::count());

    // Assert that the response contains the announcements' titles
    $response->assertSee(Announcement::first()->title)
        ->assertSee(Announcement::latest()->first()->title);
});

it('can update an existing announcement', function () {
    // Create a test announcement
    $announcement = Announcement::factory()->create();

    // Update the announcement
    $response = $this->post(route('announcements.update'), [
        'title' => 'Updated announcement',
        'body' => 'This is an updated test announcement',
        'expires_on' => now()->addDays(14)->toDateString(),
        'target_group' => ['all', 'teachers'],
        'id' => $announcement->id,
    ]);

    // Assert that the announcement was updated in the database
    $this->assertDatabaseHas('announcements', [
        'id' => $announcement->id,
        'title' => 'Updated announcement',
        'body' => 'This is an updated test announcement',
        'expires_on' => now()->addDays(14)->toDateString(),
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Announcement updated successfully');
});

it('cannot update an existing announcement from the previous school-year', function () {
    // Create a test announcement
    $announcement = Announcement::factory()->create([
        'school_year_id' => SchoolYear::factory()->create([
            'end_date' => now()->subDays(1),
        ])->id,
    ]);

    // Update the announcement
    $response = $this->post(route('announcements.update'), [
        'title' => 'Updated announcement',
        'body' => 'This is an updated test announcement',
        'expires_on' => now()->addDays(14)->toDateString(),
        'target_group' => ['all', 'teachers'],
        'id' => $announcement->id,
    ]);

    // Assert that the announcement was not updated in the database
    $this->assertDatabaseHas('announcements', [
        'id' => $announcement->id,
        'title' => $announcement->title,
        'body' => $announcement->body,
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('error', 'Announcement is not in the current school year');
});

it('can delete an existing announcement', function () {
    // Create a test announcement
    $announcement = Announcement::factory()->create();

    // Delete the announcement
    $response = $this->delete(route('announcements.destroy', $announcement->id));

    // Assert that the announcement was deleted from the database
    $this->assertSoftDeleted('announcements', [
        'id' => $announcement->id,
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Announcement deleted successfully');
});

// Add a test that checks that the announcement is not created if the user does not have the manage-announcements role
it('cannot create a new announcement if the user does not have the manage-announcements role', function () {
    // Remove the manage-announcements role from the user
    $this->admin->user->roles()->detach(['manage-announcements']);

    // Create a new announcement
    $response = $this->post(route('announcements.create'), [
        'title' => 'New announcement',
        'body' => 'This is a test announcement',
        'expires_on' => now()->addDays(7)->toDateString(),
        'target_group' => ['all', 'students'],
    ]);

    // Assert that the announcement was not created in the database
    $this->assertDatabaseMissing('announcements', [
        'title' => 'New announcement',
        'body' => 'This is a test announcement',
        'expires_on' => now()->addDays(7)->toDateString(),
    ]);

    // Assert that the user was redirected back with a forbidden message
    $response->assertForbidden();
});

it('cannot update an existing announcement if the user does not have the manage-announcements role', function () {
    // Create a test announcement
    $announcement = Announcement::factory()->create();

    // Remove the manage-announcements role from the user
    $this->admin->user->roles()->detach(['manage-announcements']);

    // Update the announcement
    $response = $this->post(route('announcements.update'), [
        'title' => 'Updated announcement',
        'body' => 'This is an updated test announcement',
        'expires_on' => now()->addDays(14)->toDateString(),
        'target_group' => ['all', 'teachers'],
        'id' => $announcement->id,
    ]);

    // Assert that the announcement was not updated in the database
    $this->assertDatabaseMissing('announcements', [
        'id' => $announcement->id,
        'title' => 'Updated announcement',
        'body' => 'This is an updated test announcement',
        'expires_on' => now()->addDays(14)->toDateString(),
    ]);

    // Assert that the user was redirected back with a forbidden message
    $response->assertForbidden();
});

it('cannot delete an existing announcement if the user does not have the manage-announcements role', function () {
    // Create a test announcement
    $announcement = Announcement::factory()->create();

    // Remove the manage-announcements role from the user
    $this->admin->user->roles()->detach(['manage-announcements']);

    // Delete the announcement
    $response = $this->delete(route('announcements.destroy', $announcement->id));

    // Assert that the announcement was not deleted from the database
    $this->assertDatabaseHas('announcements', [
        'id' => $announcement->id,
    ]);

    // Assert that the user was redirected back with a forbidden message
    $response->assertForbidden();
});
