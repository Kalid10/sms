<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BatchScheduleImportEvent implements ShouldBroadcast
{
    public string $message;

    public string $type;

    public function __construct($type, $message)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('batch-schedule-import');
    }

    public function broadcastAs(): string
    {
        return 'batch-schedule-import';
    }
}
