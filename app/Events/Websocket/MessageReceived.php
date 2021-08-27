<?php

namespace App\Events\Websocket;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Swoole\WebSocket\Frame;

class MessageReceived
{
    public function __construct(
        public Application $app,
        public Application $sandbox,
        public Frame $frame
    )
    {
    }
}
