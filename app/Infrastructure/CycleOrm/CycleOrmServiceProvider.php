<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\RepositoryInterface;
use Cycle\ORM\Collection\IlluminateCollectionFactory;
use Cycle\ORM\EntityManager;
use Cycle\ORM\EntityManagerInterface;
use Cycle\ORM\Factory;
use Cycle\ORM\FactoryInterface;
use Cycle\ORM\ORM;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\SchemaInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Infrastructure\CycleOrm\Auth\UserProvider;

final class CycleOrmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->initFactory();
        $this->initOrm();
        $this->app->bind(EntityManagerInterface::class, EntityManager::class);
        $this->app->singleton(RepositoryInterface::class, FileRepository::class);
    }

    public function boot(): void
    {
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
        Auth::resolved(function ($auth) {
            $auth->provider('cycleorm', static function ($app, array $config): UserProvider {
                return new UserProvider(
                    $app[ORMInterface::class],
                    $app[EntityManagerInterface::class],
                    $config['model'],
                    $app['hash'],
                );
            });
        });
    }
}
