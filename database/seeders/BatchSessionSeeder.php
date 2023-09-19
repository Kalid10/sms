<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchSession;
use App\Models\Quarter;
use Illuminate\Database\Seeder;

class BatchSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batch = Batch::with('schedule.batchSubject.teacher')->where('id', 1)->first();

        $activeQuarter = Quarter::getActiveQuarter();

        // For every batch schedule, create a session
        foreach ($batch->schedule as $key => $batchSchedule) {
            $batchSchedule->sessions()->create([
                'date' => fake()->dateTimeBetween('-1 week', '+1 week'),
                'teacher_id' => $batchSchedule->batchSubject->teacher_id,
                'status' => $key < 3 ? BatchSession::STATUS_COMPLETED : ($key === 3 ? BatchSession::STATUS_IN_PROGRESS : BatchSession::STATUS_SCHEDULED),
                'quarter_id' => $activeQuarter->id,
            ]);
        }
    }
}
