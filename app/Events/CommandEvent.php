<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommandEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $command;
    public $result;
    public $userId;

    public function __construct($command, $result, $userId)
    {
        $this->command = $command;
        $this->result = $result;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new Channel('console-channel');
    }

    public function broadcastAs()
    {
        return 'command';
    }
}