<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentFeeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;

    public string $type;

    public function __construct($type, $message)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('student-fee');
    }

    public function broadcastAs(): string
    {
        return 'student-fee';
    }
}
