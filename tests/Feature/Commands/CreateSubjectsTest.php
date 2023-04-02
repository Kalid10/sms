<?php

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
it('creates subjects successfully', function () {
    // Make sure there are no subjects in the database before running the command
    expect(Subject::count())->toBe(0);

    // Run the command
    $this->artisan('app:create-subjects')
        ->assertExitCode(0);

    // Check if the subjects were created
    expect(Subject::count())->toBeGreaterThan(5);
});
