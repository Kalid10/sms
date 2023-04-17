<?php

namespace App\Helpers;

use App\Models\BatchSession;
use App\Models\Level;
use Illuminate\Support\Facades\Log;

class GenerateBatchSessions
{
    public static function generate()
    {
        $levels = Level::all();

        // Get the dates for the next week
        $nextWeekDates = self::getNextWeekDates();

        // Iterate through each level
        $levels->each(function ($level) use ($nextWeekDates) {
            // Iterate through each active batch of the level
            $level->activeBatches()->each(function ($batch) use ($nextWeekDates) {
                // Iterate through each schedule of the batch
                $batch->schedule->each(function ($schedule) use ($nextWeekDates) {
                    // Iterate through the dates of the next week
                    foreach ($nextWeekDates as $nextWeekDate) {
                        if (strtolower($schedule->day_of_week) === strtolower($nextWeekDate['day'])) {
                            BatchSession::create([
                                'date' => $nextWeekDate['date'],
                                'batch_schedule_id' => $schedule->id,
                                'teacher_id' => $schedule->batchSubject->teacher_id ?? null,
                                'status' => BatchSession::STATUS_SCHEDULED,
                            ]);
                        }
                    }
                });
            });
        });

        Log::info('Batch sessions for the next week have been generated.');
    }

    /**
     * Get an array of Carbon dates for the next week.
     */
    /**
     * Get an array of Carbon dates with day names for the next week.
     */
    protected static function getNextWeekDates(): array
    {
        $startOfWeek = now()->addWeek()->startOfWeek();
        $endOfWeek = now()->addWeek()->endOfWeek();

        $dates = [];

        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $dates[] = [
                'date' => $date->copy(),
                'day' => $date->format('l'),
            ];
        }

        return $dates;
    }
}
