<?php

namespace App\Helpers;

use App\Models\Batch;
use App\Models\BatchSession;
use Carbon\Carbon;

class BatchSessionHelper
{
    /**
     * @return void
     *
     * Sync the Batch Session statuses for a given Batch.
     * Runs only once per minute for a given Batch.
     */
    public static function sync(Batch $batch): void
    {
        if (! self::shouldSync($batch)) {
            return;
        }

        // Initialize $synced to false and $batchSession
        // to an "in_progress" BatchSession if any
        $synced = false;
        $batchSession = $batch->inProgressSession();

        // Run the loop until $synced is true
        while (! $synced) {
            // If $batchSession is null, compute the BatchSession that
            // overlaps with the current time and set it to "in_progress",
            // if any.
            // Finally, set the statuses of all the BatchSessions that
            // have passed the current time to "completed".
            if (is_null($batchSession)) {
                $batchSessionInRange = self::findBatchSessionInRange($batch);

                // If no BatchSession that overlaps with the current time
                // is found, find and store the next upcoming BatchSession
                if (is_null($batchSessionInRange)) {
                    $batchSessionInRange = self::findUpcomingBatchSession($batch);
                } else {
                    $batchSessionInRange->update(['status' => BatchSession::STATUS_IN_PROGRESS]);
                }

                BatchSession::whereHas('batchSchedule', function ($query) use ($batch) {
                    $query->where('batch_id', $batch->id);
                })
                    ->where('id', '<', $batchSessionInRange->id)
                    ->whereNot('status', BatchSession::STATUS_COMPLETED)
                    ->update([
                        'status' => BatchSession::STATUS_COMPLETED,
                    ]);

                $synced = true;

                continue;
            }

            // If $batchSession is not null, check if it overlaps
            // with the current time. If it does, set it to "in_progress",
            // then break.
            if (self::isBatchSessionInRange($batchSession)) {
                $batchSession->update([
                    'status' => BatchSession::STATUS_IN_PROGRESS,
                ]);

                $synced = true;

                continue;
            }

            // If $batchSession is not null, and it does not overlap
            // with the current time, increment the $batchSession
            // to the next one and continue the loop.
            $batchSession = $batch->sessions
                ->where('id', $batchSession->id + 1)
                ->first();
        }

        $batch['session_last_synced'] = Carbon::now();
        $batch->save();
    }

    /**
     * @return bool
     *
     * Check whether the batch session should be synced or not
     * - Disallow if Batch is not active
     * - Disallow if session_last_synced is null
     * - Disallow if session_last_synced is less than a minute ago
     */
    private static function shouldSync(Batch $batch): bool
    {
        if (! $batch->isActive()) {
            return false;
        }

        if ($batch['session_last_synced'] === null) {
            return true;
        } else {
            return $batch->session_last_synced->diffInMinutes(Carbon::now());
        }
    }

    /**
     * @return bool
     *
     * Check whether the given BatchSession overlaps with the current time
     */
    public static function isBatchSessionInRange(BatchSession $batchSession): bool
    {
        $schoolPeriod = $batchSession->schoolPeriod;

        return $batchSession->date->isToday() &&
            Carbon::createFromDate($schoolPeriod->start_time)->isBefore(now()) &&
            Carbon::createFromDate($schoolPeriod->start_time)
                ->addMinutes($schoolPeriod->duration)
                ->isAfter(now());
    }

    /**
     * @return BatchSession|null
     *
     * Find the BatchSession that overlaps with the current time
     */
    public static function findBatchSessionInRange(Batch $batch): ?BatchSession
    {
        $now = Carbon::now();
        $today = $now->today();

        return $batch
            ->sessions
            ->where('date', $today)
            ->filter(function ($session) use ($now) {
                return (Carbon::createFromDate($session->schoolPeriod->start_time)
                    ->format('His') < $now->format('His')) &&
                    (Carbon::createFromDate($session->schoolPeriod->start_time)
                        ->addMinutes($session->schoolPeriod->duration)
                        ->format('His') > $now->format('His'));
            })
            ->first();
    }

    /**
     * @return BatchSession|null
     *
     * Find the next upcoming BatchSession
     */
    public static function findUpcomingBatchSession(Batch $batch): ?BatchSession
    {
        $now = Carbon::now();
        $today = $now->today();

        return $batch
            ->sessions
            ->where('date', '>=', $today)
            ->filter(function ($session) use ($now) {
                return
                    $session->date->greaterThan(Carbon::today()) ||
                    Carbon::createFromDate($session->schoolPeriod->start_time)->format('His') >
                        $now->format('His');
            })
            ->first();
    }
}
