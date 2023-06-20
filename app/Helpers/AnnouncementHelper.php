<?php

namespace App\Helpers;

use App\Models\Announcement;
use App\Models\Batch;
use App\Notifications\AnnouncementReleased;
use Illuminate\Support\Facades\Notification;

class AnnouncementHelper
{
    public static function handleNotifications(Announcement $announcement): void
    {
        $targetGroups = collect($announcement->target_group);
        $targetBatches = collect($announcement->target_batches);

        if ($targetGroups->has('all')) {
            $targetGroups = collect(['guardians', 'teachers', 'admins']);
        }

        if ($targetBatches->count() === 0) {
            $targetBatches = Batch::active()->pluck('id');
        }

        $targetGroups->each(function ($target) use ($targetBatches, $announcement) {
            switch ($target) {
                case 'guardians':
                    $guardians = self::fetchGuardians($targetBatches);

                    // TODO: remove this filter once Mailable is implemented
                    $guardians = $guardians->filter(function ($guardian) {
                        return count($guardian->fcm_tokens) > 0;
                    });

                    Notification::send($guardians, new AnnouncementReleased($announcement));
                    break;

                case 'teachers':
                case 'admins':
                default:
                    // TODO: implement appropriate user fetching logic
                    break;
            }
        });
    }

    private static function fetchGuardians($batchIds)
    {
        return Batch::whereIn('id', $batchIds)->with('base_students.guardian.user')
            ->get()->map(function ($batch) {
                return $batch->base_students
                    ->pluck('guardian')
                    ->pluck('user');
            })->flatten()->unique();
    }
}
