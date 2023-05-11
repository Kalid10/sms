<?php

namespace Database\Seeders;

use App\Jobs\GenerateBatchSchedulesJob;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Simulation\BatchSeeder;
use Database\Seeders\Simulation\BatchSubjectSeeder;
use Database\Seeders\Simulation\FamilySeeder;
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
            TeacherSeeder::class,
        ]);

        GenerateBatchSchedulesJob::dispatchSync();
    }

    private function admin()
    {
        return User::factory()->create([
            'name' => 'Biniyam Lemma',
            'email' => 'biniyam.lemma@gibson.edu.et',
            'type' => User::TYPE_ADMIN,
        ]);
    }

    protected function gender(): string
    {
        return rand(0, 1) ? 'male' : 'female';
    }
}
