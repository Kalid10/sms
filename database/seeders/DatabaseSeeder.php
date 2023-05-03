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
        Teacher::factory(500)->create();
        Guardian::factory(50)->create();
        Student::factory(50)->create();

        $this->call([
            SchoolYearSeeder::class,
            BatchSeeder::class,
            HomeroomTeacherSeeder::class,
            SchoolScheduleSeeder::class,
            SchoolPeriodSeeder::class,
            BatchSubjectsSeeder::class,
            // BatchSchedule seeder class is disabled because it will clash when the real batch schedule is generated,
            // enable it whenever you need small sample data
            //            BatchScheduleSeeder::class,
            BatchSessionSeeder::class,
            BatchStudentSeeder::class,
            TeacherFeedbackSeeder::class,
            // Run this seeder after populating the batch sessions
            //            LessonPlanSeeder::class,
            AssessmentTypeSeeder::class,
        ]);
    }
}
