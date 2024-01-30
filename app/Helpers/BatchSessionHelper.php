<?php

namespace App\Helpers;

use App\Models\Batch;
use App\Models\BatchSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class BatchSessionHelper
{
    /**
     * Sync the Batch Sessions of a given Batch
     * - Get the scheduled BatchSession
     * - Set all previous BatchSessions to "completed"
     * - Set the scheduled BatchSession to "scheduled"
     * - Set the last "completed" BatchSession to "in_progress" if it overlaps with the current time
     */
    public static function sync(Batch $batch, bool $force): void
    {
        if (! self::shouldSync($batch, $force)) {
            return;
        }

        Log::info('syncing for batch '.$batch->id);

        $scheduledBatchSessions = self::findUpcomingBatchSessions($batch);
        $previousBatchSessions = self::findPreviousBatchSessions($batch);

        $scheduledBatchSessions->where('status', '!=', BatchSession::STATUS_SCHEDULED)
            ->each(function (BatchSession $batchSession) {
                $batchSession->update(['status' => BatchSession::STATUS_SCHEDULED]);
            });

        $previousBatchSessions->where('status', '!=', BatchSession::STATUS_COMPLETED)
            ->each(function (BatchSession $batchSession) {
                $batchSession->update(['status' => BatchSession::STATUS_COMPLETED]);
            });

        $potentiallyInProgressSession = self::findPotentiallyInProgressSession($batch);
        if ($potentiallyInProgressSession) {
            self::doesBatchSessionOverlapWithCurrentTime($potentiallyInProgressSession) ?
                $potentiallyInProgressSession->update(['status' => BatchSession::STATUS_IN_PROGRESS]) :
                $potentiallyInProgressSession->update(['status' => BatchSession::STATUS_COMPLETED]);
        }

        $batch['session_last_synced'] = Carbon::now();
        $batch->save();
    }

    /**
     * Fetch the previous Batch Sessions for a given Batch
     */
    public static function findPreviousBatchSessions(Batch $batch): Collection
    {
        return $batch->load('sessions')
            ->sessions
            ->where('date', '<=', Carbon::now())
            ->sortByDesc('date');
    }

    /**
     * Fetch the upcoming Batch Sessions for a given Batch
     */
    public static function findUpcomingBatchSessions(Batch $batch): Collection
    {
        return $batch
            ->load('sessions')
            ->sessions
            ->where('date', '>=', Carbon::now())
            ->sortBy('date');
    }

    /**
     * Fetch the Batch Session that could be in progress
     *
     * @return ?BatchSession
     */
    public static function findPotentiallyInProgressSession(Batch $batch): ?BatchSession
    {
        Log::info('finding potentially in progress session: ');
        Log::info(json_encode(self::findPreviousBatchSessions($batch)->values()->first()));

        return self::findPreviousBatchSessions($batch)?->values()?->first();
    }

    /**
     * Check if a Batch Session range overlaps with current time
     */
    public static function doesBatchSessionOverlapWithCurrentTime(BatchSession $batchSession): bool
    {
        $batchSessionStartTime = $batchSession->load('batchSchedule.schoolPeriod')->date;
        $batchSessionEndTime = $batchSessionStartTime->copy()->addMinutes($batchSession->batchSchedule->schoolPeriod->duration);

        return Carbon::now()->addSecond()->between($batchSessionStartTime, $batchSessionEndTime);
    }

    /**
     * Check whether the batch session should be synced or not
     * - Disallow if Batch is not active
     * - Allow if ignore is true
     * - Allow if session_last_synced is null
     * - Allow if session_last_synced is at least a minute ago
     */
    private static function shouldSync(Batch $batch, bool $ignore = false): bool
    {
        if ($ignore) {
            return true;
        }

        if (! $batch->isActive()) {
            return false;
        }

        if ($batch['session_last_synced'] === null) {
            return true;
        } else {
            return $batch->session_last_synced->diffInMinutes(Carbon::now());
        }
    }
}
