<?php
declare(strict_types=1);

namespace App\Websocket;

use Swoole\Http\Server as SwooleServer;

class Server
{
    public function __construct(
        private ConnectionsRepository $connections,
        private SwooleServer          $server,
    )
    {
    }

    public function sendEvent(array $event)
    {
        $event['timestamp'] = time();

        foreach ($this->connections->all() as $client => $connection) {
            $this->server->push($client, json_encode($event));
        }
    }
}
