<?php
declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseManager;
use Cycle\Database\DatabaseProviderInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

final class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DatabaseProviderInterface::class, static function ($app) {
            $manager = new DatabaseManager(
                new DatabaseConfig(config('cycle.database'))
            );

            $manager->setLogger($app['log']->driver(config('cycle.database.logger')));

            return $manager;
        });

        $this->app->alias(DatabaseProviderInterface::class, DatabaseManager::class);
    }
}
