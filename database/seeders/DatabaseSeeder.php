<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Guardian;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
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

        // Populate subjects
        Artisan::call('app:create-subjects');

        // Assign all roles to the user
        $user->roles()->attach(Role::all());

        // Populate users
        Admin::factory(5)->create();
        Teacher::factory(50)->create();
        Guardian::factory(50)->create();
        Student::factory(50)->create();

        $this->call([
            SchoolYearSeeder::class,
            BatchSeeder::class,
            BatchSubjectsSeeder::class,
            HomeroomTeacherSeeder::class,
            SchoolScheduleSeeder::class,
            SchoolPeriodSeeder::class,
            BatchScheduleSeeder::class,
            BatchSessionSeeder::class,
            BatchStudentSeeder::class,
        ]);
    }
}
