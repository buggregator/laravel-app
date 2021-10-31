<?php

namespace App\Events\Websocket;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TerminalWrite implements ShouldBroadcastNow
{
    public function __construct(public string $message, public bool $newline)
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('terminal');
    }
}
