<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchSchedule;
use Illuminate\Database\Seeder;

class BatchScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createBatchSchedules();
    }

    // Create a function that adds 5 batch schedules to the database
    private function createBatchSchedules(): void
    {
        $batch = Batch::find(1);

        $schoolPeriods = $batch->level->levelCategory->schoolPeriods;

        foreach ($schoolPeriods as $schoolPeriod) {
            if ($schoolPeriod->is_custom) {
                continue;
            }

            BatchSchedule::create([
                'batch_id' => $batch->id,
                'school_period_id' => $schoolPeriod->id,
                'day_of_week' => 1,
                'batch_subject_id' => fake()->randomElement($batch->subjects->pluck('id')->toArray()),
            ]);
        }
    }
}
