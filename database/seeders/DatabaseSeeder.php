<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'type' => User::TYPE_ADMIN,
        ]);

        // Populate roles
        Artisan::call('app:create-roles');

        // Populate levels
        Artisan::call('app:create-levels');

        // Assign all roles to the user
        $user->roles()->attach(Role::all());
    }
}
