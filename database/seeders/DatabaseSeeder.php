<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        // Assign all roles to the user
        $user->roles()->attach(Role::all());
    }
}
