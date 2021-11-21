<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\Migrations\Config\MigrationConfig;
use Cycle\Migrations\FileRepository;
use Cycle\Migrations\Migrator;
use Cycle\ORM\SchemaInterface;
use Illuminate\Support\ServiceProvider;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;

final class SchemaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            Commands\Schema\GenerateCommand::class,
            Commands\Schema\MigrateCommand::class,
            Commands\Schema\RefreshCommand::class,
            Commands\Schema\RenderCommand::class,
        ]);

        $this->registerMigrator();
        $this->registerSchemaManager();

        $this->app->singleton(SchemaInterface::class, static function ($app) {
            return $app[SchemaManager::class]->createSchema();
        });
    }

    protected function registerMigrator(): void
    {
        $this->app->singleton(Migrator::class, function () {
            $config = new MigrationConfig(
                config('cycle.schema.migrations')
            );

            return new Migrator(
                $config,
                $this->app[DatabaseProviderInterface::class],
                new FileRepository($config),
            );
        });
    }

    private function registerSchemaManager(): void
    {
        $this->app->singleton(SchemaManager::class, function () {
            $tokenizer = new Tokenizer(
                new TokenizerConfig(config('cycle.schema.tokenizer'))
            );

            return new SchemaManager(
                $tokenizer->classLocator(),
                $this->app[DatabaseProviderInterface::class],
                $this->app['cache']->store(
                    config('cycle.schema.cache.storage')
                )
            );
        });
    }
}
