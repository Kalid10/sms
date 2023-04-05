<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StudentImportEvent implements ShouldBroadcast
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
        return new PrivateChannel('students-import');
    }

    public function broadcastAs(): string
    {
        return 'students-import';
    }
}
