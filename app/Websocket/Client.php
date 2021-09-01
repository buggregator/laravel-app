<?php
declare(strict_types=1);

namespace App\Websocket;

class Client
{
    public function __construct(private string $server = 'ws://127.0.0.1:8000')
    {
    }

    public function sendEvent(array $event): void
    {
        $client = new \WebSocket\Client($this->server);
        $client->text(json_encode($event));
        $client->close();
    }
}
