<?php

namespace App\Listeners\Websocket;

use App\Events\Websocket\ConnectionOpened;
use App\EventsRepository;
use Swoole\Http\Server;

class BroadcastStoredEvents
{
    public function __construct(
        private EventsRepository $events,
        private Server           $server
    )
    {
    }

    public function handle(ConnectionOpened $event)
    {
        try {
            foreach ($this->events->all() as $e) {
                $this->server->push($event->fd, json_encode($e));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
