<?php
declare(strict_types=1);

namespace App\Websocket;

use App\Contracts\WebsocketClient;
use Swoole\Http\Server as SwooleServer;

class SwooleClient implements WebsocketClient
{
    public function __construct(
        private ConnectionsRepository $connections,
        private SwooleServer          $server,
    )
    {
    }

    public function sendEvent(array $event): void
    {
        $event['timestamp'] = time();

        foreach ($this->connections->all() as $client => $connection) {
            $this->server->push($client, json_encode($event));
        }
    }
}
