<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\RepositoryInterface;
use Cycle\ORM\Collection\IlluminateCollectionFactory;
use Cycle\ORM\Factory;
use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Transaction;
use Cycle\ORM\TransactionInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\CycleOrm\Auth\UserProvider;

final class CycleOrmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->initFactory();
        $this->initOrm();
        $this->app->bind(TransactionInterface::class, Transaction::class);
        $this->app->singleton(RepositoryInterface::class, FileRepository::class);
        $this->registerAuthUserProvider();
    }

    private function initFactory(): void
    {
        $this->app->singleton(FactoryInterface::class, static function ($app) {
            return new Factory(
                dbal: $app[DatabaseProviderInterface::class],
                factory: new Container($app),
                defaultCollectionFactory: new IlluminateCollectionFactory()
            );
        });
    }

    private function initOrm(): void
    {
        $this->app->singleton(ORMInterface::class, static function ($app): ORM {
            return new ORM(
                factory: $app[FactoryInterface::class],
                schema: $app[SchemaInterface::class]
            );
        });
    }

    private function registerAuthUserProvider(): void
    {
        $this->app['auth']->provider('cycle', function ($app, $config) {
            return new UserProvider(
                $app[ORMInterface::class],
                $app[TransactionInterface::class],
                $config['model'],
                $app['hash'],
            );
        });
    }
}
