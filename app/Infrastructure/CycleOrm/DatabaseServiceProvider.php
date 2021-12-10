<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\Database;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Cycle\Database\DatabaseProviderInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DatabaseProviderInterface::class, static function ($app) {
            return new DatabaseManager(
                new DatabaseConfig(
                    config('cycle.database')
                ),
                new LoggerFactory(
                    $app['log'],
                    config('cycle.database.logger')
                )
            );
        });

        $this->app->alias(DatabaseProviderInterface::class, DatabaseManager::class);
        $this->app->alias(DatabaseInterface::class, Database::class);

        $this->app->terminating(static function (Application $app): void {
            if ($app->runningUnitTests()) {
                return;
            }

            $dbal = $app[DatabaseProviderInterface::class];
            foreach ($dbal->getDrivers() as $driver) {
                $driver->disconnect();
            }
        });
    }
}
