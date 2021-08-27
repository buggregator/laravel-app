<?php
declare(strict_types=1);

namespace App\Listeners\Websocket;

use App\Events\Websocket\MessageReceived;
use App\WebsocketServer;

class BroadcastReceivedEvent
{
    public function __construct(
        private WebsocketServer $ws
    )
    {
    }

    public function handle(MessageReceived $event)
    {
        try {
            $this->ws->sendEvent(json_decode($event->frame->data, true));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}

