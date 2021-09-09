<?php
declare(strict_types=1);

namespace App\Websocket;

use Laravel\Octane\Octane;
use Swoole\Table;

class ConnectionsRepository
{
    public function __construct(private Octane $octane)
    {
    }

    public function all(): \Generator
    {
        foreach ($this->table() as $connection) {
            yield $connection['client'] => $connection;
        }
    }

    public function store(int $fd): void
    {
        $this->table()->set((string)$fd, ['client' => $fd]);
    }

    public function close(int $fd): void
    {
        $this->table()->del((string)$fd);
    }

    private function table(): Table
    {
        return $this->octane->table('connections');
    }
}
