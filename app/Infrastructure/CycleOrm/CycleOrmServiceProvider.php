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
use Spiral\Core\Container as SpiralContainer;

final class CycleOrmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->initContainer();
        $this->initFactory();
        $this->initOrm();
    }

    private function initContainer(): void
    {
        $this->app->singleton(SpiralContainer::class, static function($app) {
            $container = new SpiralContainer();

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
            $orm = new \Cycle\ORM\ORM(
                $app[FactoryInterface::class],
                $app[SchemaInterface::class]
            );

            $app[SpiralContainer::class]
                ->bind(TransactionInterface::class, new Transaction($orm));

            return $orm;
        });
    }
}
