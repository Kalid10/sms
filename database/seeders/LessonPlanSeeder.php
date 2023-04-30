<?php

namespace Database\Seeders;

use App\Models\BatchSession;
use App\Models\LessonPlan;
use Faker\Factory;
use Illuminate\Database\Seeder;

class LessonPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $batchSessions = BatchSession::with('batchSubject')->whereHas('batchSubject')->get();

        // Calculate the 1/4th of the total batch sessions
        $oneFourthSessionsCount = (int) ($batchSessions->count() / 4);

        // Select the first 1/4th of the batch sessions
        $selectedBatchSessions = $batchSessions->take($oneFourthSessionsCount);

        // Iterate over the selected batch sessions
        foreach ($selectedBatchSessions as $batchSession) {
            // Create a LessonPlan for the current batch session
            $lessonPlan = LessonPlan::create([
                'topic' => $faker->realText(100),
                'description' => $faker->realText(2000),
            ]);

            // Assign the lesson plan to the current batch session
            $batchSession->lessonPlan()->associate($lessonPlan);
            $batchSession->save();
        }
    }
}
