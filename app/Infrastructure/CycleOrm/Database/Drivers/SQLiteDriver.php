<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Database\Drivers;

use Cycle\Database\Config\DriverConfig;
use Cycle\Database\Driver\SQLite\SQLiteCompiler;
use Cycle\Database\Driver\SQLite\SQLiteHandler;
use Cycle\Database\Query\QueryBuilder;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;

class SQLiteDriver extends \Cycle\Database\Driver\SQLite\SQLiteDriver
{
    public static function create(DriverConfig $config): static
    {
        return new self(
            $config,
            new SQLiteHandler(),
            new SQLiteCompiler('""'),
            QueryBuilder::defaultBuilder()
        );
    }

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
        try {
            $this->getLock()->forceRelease();
        } catch (\Throwable $e) {
            $this->logger?->error(
                \sprintf('Lock [%s] can not be released. Reason: %s', 'driver.'.$this->getType(), $e->getMessage())
            );
        }
        parent::disconnect();
    }
}
