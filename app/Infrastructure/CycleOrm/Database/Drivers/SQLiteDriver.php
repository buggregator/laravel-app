<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Database\Drivers;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;

class SQLiteDriver extends \Cycle\Database\Driver\SQLite\SQLiteDriver
{
    private function getLock(): Lock
    {
        return Cache::lock('driver.'.$this->getType(), 10);
    }

    public function connect(): void
    {
        if ($this->getLock()->get()) {
            parent::connect();
        }
    }

    public function disconnect(): void
    {
        parent::disconnect();
        $this->getLock()->forceRelease();
    }
}
