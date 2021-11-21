<?php

declare(strict_types=1);

namespace Infrastructure\RoadRunner\Broadcast;

use App\Contracts\WebsocketClient;
use LogicException;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Broadcast\Broadcast;

final class RoadrunnerClient implements WebsocketClient
{
    public function __construct(private $host)
    {
    }

    public function sendEvent(string $topic, array $event): void
    {
        $broadcast = new Broadcast(RPC::create($this->host));

        if (! $broadcast->isAvailable()) {
            throw new LogicException('The [broadcast] plugin not available');
        }

        $broadcast->publish($topic, json_encode($event));
    }
}
