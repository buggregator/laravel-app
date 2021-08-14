<?php
declare(strict_types=1);

namespace App\Websocket;

use Laravel\Octane\Octane;

class ConnectionsRepository
{
    public function __construct(private Octane $octane)
    {
    }

    public function all(): \Generator
    {
        foreach ($this->octane->table('connections') as $connection) {
            yield $connection['client'] => $connection;
        }
    }
}
