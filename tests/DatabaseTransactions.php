<?php

declare(strict_types=1);

namespace Tests;

use Cycle\Database\DatabaseProviderInterface;

trait DatabaseTransactions
{
    public function beginDatabaseTransaction()
    {
        $database = $this->app->make(DatabaseProviderInterface::class)->database();

        $driver = $database->getDriver();
        $driver->beginTransaction();

        $this->beforeApplicationDestroyed(function () use ($driver) {
            $driver->rollbackTransaction();
            $driver->disconnect();
        });
    }
}
