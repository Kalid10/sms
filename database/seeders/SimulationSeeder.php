<?php

namespace Database\Seeders;

use App\Jobs\GenerateBatchSchedulesJob;
use App\Models\BatchScheduleConfig;
use App\Models\Role;
use App\Models\SchoolYear;
use App\Models\User;
use Database\Seeders\Simulation\BatchSeeder;
use Database\Seeders\Simulation\BatchSubjectSeeder;
use Database\Seeders\Simulation\FamilySeeder;
use Database\Seeders\Simulation\HomeroomTeacherSeeder;
use Database\Seeders\Simulation\LevelCategorySeeder;
use Database\Seeders\Simulation\LevelSeeder;
use Database\Seeders\Simulation\SchoolYearSeeder;
use Database\Seeders\Simulation\SubjectSeeder;
use Database\Seeders\Simulation\TeacherSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('app:create-roles');
        $this->admin()->roles()->attach(Role::all());

        $this->call([
            SubjectSeeder::class,
            LevelCategorySeeder::class,
            LevelSeeder::class,
            SchoolYearSeeder::class,
            FamilySeeder::class,
            BatchSeeder::class,
            BatchSubjectSeeder::class,
            SchoolPeriodSeeder::class,
        ]);

        $this->call([
            TeacherSeeder::class,
            HomeroomTeacherSeeder::class,
            AssessmentTypeSeeder::class,
            AssessmentSeeder::class,
            LessonPlanSeeder::class,
            GradeScaleSeeder::class,
            AnnouncementSeeder::class,
            SchoolScheduleSeeder::class,
        ]);
        //

        BatchScheduleConfig::create([
            'school_year_id' => SchoolYear::getActiveSchoolYear()?->id ?? 1,
            'max_periods_per_day' => 8,
            'max_periods_per_week' => 30,
        ]);

        $scheduleJob = new GenerateBatchSchedulesJob();
        $scheduleJob->createSchedule();

        Artisan::call('app:generate-batch-sessions', ['--duration' => 'weekly']);
    }

    private function admin()
    {
        $user = User::factory()->create([
            'name' => 'School Admin',
            'email' => 'biniyam@rigel.studio',
            'type' => User::TYPE_ADMIN,
        ]);

        return $user->admin()->create([
            'position' => 'System Administrator',
        ])->user;
    }

    protected function gender(): string
    {
        return rand(0, 1) ? 'male' : 'female';
    }
}
