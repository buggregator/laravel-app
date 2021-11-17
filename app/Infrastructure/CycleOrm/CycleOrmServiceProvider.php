<?php
declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\Collection\IlluminateCollectionFactory;
use Cycle\ORM\Factory;
use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Transaction;
use Cycle\ORM\TransactionInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\CycleOrm\Auth\UserProvider;
use Spiral\Core\Container as SpiralContainer;

final class CycleOrmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->initContainer();
        $this->initFactory();
        $this->initOrm();
        $this->app->bind(TransactionInterface::class, Transaction::class);
        $this->registerAuthUserProvider();
    }

    private function initContainer(): void
    {
        $this->app->singleton(SpiralContainer::class, static function($app) {
            $container = new SpiralContainer();
            $container->bind(TransactionInterface::class, Transaction::class);
            $container->bind(ORMInterface::class, fn() => $app[ORMInterface::class]);

            return $container;
        });

        $this->app->alias(SpiralContainer::class, \Spiral\Core\FactoryInterface::class);
    }

    private function initFactory(): void
    {
        $this->app->singleton(FactoryInterface::class, static function($app) {
            return new Factory(
                dbal: $app[DatabaseProviderInterface::class],
                factory: $app[SpiralContainer::class],
                defaultCollectionFactory: new IlluminateCollectionFactory()
            );
        });
    }

    private function initOrm(): void
    {
        $this->app->singleton(ORMInterface::class, static function ($app) {
            return new \Cycle\ORM\ORM(
                $app[FactoryInterface::class],
                $app[SchemaInterface::class]
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
