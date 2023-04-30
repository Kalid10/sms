<?php

namespace App\Helpers;

use App\Models\BatchSession;
use App\Models\Level;
use Illuminate\Support\Facades\Log;

class GenerateBatchSessions
{
    public static function generate(string $duration = 'weekly'): void
    {
        // Get the dates for the next week or month
        $nextDates = $duration === 'monthly' ? self::getNextMonthDates() : self::getNextWeekDates();

        // Set the chunk size
        $chunkSize = 3;

        // Use chunk to process levels in smaller parts
        Level::orderBy('id')->chunk($chunkSize, function ($levels) use ($nextDates) {
            // Iterate through each level
            $levels->each(function ($level) use ($nextDates) {
                // Iterate through each active batch of the level
                $level->activeBatches()->each(function ($batch) use ($nextDates) {
                    // Iterate through each schedule of the batch
                    $batch->schedule->each(function ($schedule) use ($nextDates) {
                        // Iterate through the dates of the next week or month
                        foreach ($nextDates as $nextDate) {
                            if (strtolower($schedule->day_of_week) === strtolower($nextDate['day'])) {
                                BatchSession::create([
                                    'date' => $nextDate['date'],
                                    'batch_schedule_id' => $schedule->id,
                                    'teacher_id' => $schedule->batchSubject->teacher_id ?? null,
                                    'status' => BatchSession::STATUS_SCHEDULED,
                                ]);
                            }
                        }
                    });
                });
            });
        });

        Log::info('Batch sessions for the next '.$duration.' have been generated.');
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

    protected static function getNextMonthDates(): array
    {
        $startOfMonth = now()->addMonth()->startOfMonth();
        $endOfMonth = now()->addMonth()->endOfMonth();

        $dates = [];

        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = [
                'date' => $date->copy(),
                'day' => $date->format('l'),
            ];
        }

        return $dates;
    }
}
