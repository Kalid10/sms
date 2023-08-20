<?php

use App\Models\Admin;
use App\Models\LevelCategory;

beforeEach(function () {
    $this->artisan('app:create-roles');

    // Create a test admin user
    $this->admin = Admin::factory()->create();

    $this->admin->user->roles()->attach(['manage-levels']);

    // Use this user for this test
    $this->actingAs($this->admin->user);

    // Create a test level category
    $this->seed('LevelCategorySeeder');
});

it('can create a new level category', function () {
    // Create a new level category
    $response = $this->post(route('level-categories.create'), [
        'name' => 'New level category',
    ]);

    // Assert that the level category was created in the database
    $response->assertRedirect();
    $this->assertDatabaseHas('level_categories', [
        'name' => 'New level category',
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Level category created successfully');
});

it('can list all level categories', function () {
    // Get the level categories index page
    $response = $this->get(route('levels.level-categories.index'));

    // Check no of level categories in the database
    expect(LevelCategory::count())->toBeGreaterThan(3);

    // Assert that the response contains the level categories' names
    $response->assertSee(LevelCategory::first()->name)
        ->assertSee(LevelCategory::latest()->first()->name);
});

it('can update a level category', function () {
    $levelCategory = LevelCategory::first();

    // Update the level category
    $response = $this->post(route('level-categories.update'), [
        'id' => $levelCategory->id,
        'name' => 'Updated level category',
    ]);

    // Assert that the level category was updated in the database
    $response->assertRedirect();
    $this->assertDatabaseHas('level_categories', [
        'id' => $levelCategory->id,
        'name' => 'Updated level category',
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Level category updated successfully');
});

it('can delete an existing level category', function () {
    $levelCategory = LevelCategory::first();

    // Delete the level category
    $response = $this->delete(route('level-categories.delete', $levelCategory->id));

    // Assert that the level category was deleted from the database
    $response->assertRedirect();
    $this->assertDatabaseMissing('level_categories', [
        'id' => $levelCategory->id,
    ]);

    // Assert that the user was redirected back with a success message
    $response->assertRedirect()
        ->assertSessionHas('success', 'Level Category deleted successfully');
});

it('cannot create a new level category if the user does not have the manage-levels permission', function () {
    // Remove the manage-levels permission from the user
    $this->admin->user->roles()->detach(['manage-levels']);

    // Use this user for this test
    $this->actingAs($this->admin->user);

    // Create a new level category
    $response = $this->post(route('level-categories.create'), [
        'name' => 'New level category',
    ]);

    // Assert that the new level category was not created in the database
    $this->assertDatabaseMissing('level_categories', [
        'name' => 'New level category',
    ]);

    // Assert that the user was redirected back with a forbidden message
    $response->assertForbidden();
});
