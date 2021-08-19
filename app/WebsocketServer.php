<?php
declare(strict_types=1);

namespace App;

use App\Websocket\ConnectionsRepository;
use Swoole\Http\Server;

class WebsocketServer
{
    public function __construct(
        private ConnectionsRepository $connections,
        private Server                $server,
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
