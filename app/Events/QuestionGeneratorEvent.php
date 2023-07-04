<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QuestionGeneratorEvent implements ShouldBroadcast
{
    public string $message;

    public string $type;

    public function __construct($type, $message)
    {
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('question-generator');
    }

    public function broadcastAs(): string
    {
        return 'question-generator';
    }
}