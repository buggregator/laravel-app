<?php

namespace App\Listeners\Websocket;

use App\Events\Websocket\ConnectionOpened;
use App\Websocket\ConnectionsRepository;

class StoreConnection
{
    public function __construct(private ConnectionsRepository $connections)
    {
    }

    public function handle(ConnectionOpened $event)
    {
        $this->connections->store($event->fd);
    }
}
