<?php
declare(strict_types=1);

namespace App\Websocket;

use App\Contracts\WebsocketClient;

class Client implements WebsocketClient
{
    public function __construct(
        private string $host = 'ws://127.0.0.1:8000'
    )
    {
    }

    public function sendEvent(array $event): void
    {
        try {
            $client = new \WebSocket\Client($this->host);
            $client->text(json_encode($event));
            $client->close();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
