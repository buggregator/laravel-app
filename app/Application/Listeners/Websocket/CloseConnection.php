<?php

namespace App\Listeners\Websocket;

use App\Events\Websocket\ConnectionClosed;
use App\Events\Websocket\ConnectionDisconnected;
use App\Websocket\ConnectionsRepository;

class CloseConnection
{
    public function __construct(private ConnectionsRepository $connections)
    {
    }

    public function handle(ConnectionClosed|ConnectionDisconnected $event)
    {
        $this->connections->close($event->fd);
    }
}
