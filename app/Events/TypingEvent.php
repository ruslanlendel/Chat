<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TypingEvent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $text;
    public $id;

    public function __construct($text, $id)
    {
        $this->text = $text;
        $this->id = $id;
    }

    public function broadcastOn()
    {
        return new Channel('console-channel');
    }

    public function broadcastAs()
    {
        return 'typing';
    }
}
