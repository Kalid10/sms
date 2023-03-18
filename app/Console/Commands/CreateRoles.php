<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class CreateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles';

    // User roles to be created
    private $roles = [
        'manage-roles',
        'manage-subjects',
        'manage-levels',
        'manage-semesters',
        'manage-school-years',
    ];

    // Description for each user role
    private $roleDescriptions = [
        'Manage Roles',
        'Manage Subjects',
        'Manage Levels',
        'Manage Semesters',
        'Manage School Years',
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach ($this->roles as $key => $role) {
            Role::firstOrCreate([
                'name' => $role,
            ], [
                'description' => $this->roleDescriptions[$key],
            ]);
            $this->info('Role '.$role.' created.');
        }
    }
}
