<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    // populate roles
    Artisan::call('app:create-roles');
});

it('creates an admin successfully', function () {
    // Arrange
    $name = 'John Doe';
    $email = 'john@example.com';

    // Act
    $result = Artisan::call('app:create-admin', [
        'name' => $name,
        'email' => $email,
    ]);

    // Assert
    expect($result)->toBe(0);
    $admin = User::where('email', $email)->first();
    expect($admin)->not()->toBeNull();
    expect($admin->name)->toBe($name);
    expect($admin->type)->toBe(User::TYPE_ADMIN);
    expect($admin->roles->pluck('name')->toArray())->toContain('manage-users');
});

it('fails to create an admin with invalid email', function () {
    // Arrange
    $name = 'John Doe';
    $email = 'invalid-email';

    // Act
    $result = Artisan::call('app:create-admin', [
        'name' => $name,
        'email' => $email,
    ]);

    // Assert
    expect($result)->toBe(1);
    $admin = User::where('email', $email)->first();
    expect($admin)->toBeNull();
});
