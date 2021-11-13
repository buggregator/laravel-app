<?php
declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\Schema as ORMSchema;
use Cycle\ORM\SchemaInterface;
use Cycle\Annotated;
use Cycle\Schema\Generator\Migrations\GenerateMigrations;
use Psr\SimpleCache\CacheInterface;
use Spiral\Tokenizer\ClassLocator;
use Cycle\Migrations\Migrator;
use Cycle\Schema;

final class SchemaManager
{
    private const SCHEMA_STORAGE_KEY = 'cycle.schema';

    public function __construct(
        private ClassLocator              $classLocator,
        private DatabaseProviderInterface $database,
        private CacheInterface            $cache,
        private Migrator                  $migrator
    )
    {
    }

    public function isSyncMode(): bool
    {
        return (bool)config('cycle.schema.sync');
    }

    public function createSchema(): SchemaInterface
    {
        return new ORMSchema($this->getSchema());
    }

    public function flushSchemaData(): void
    {
        $this->cache->forget(self::SCHEMA_STORAGE_KEY);
    }

    public function compileSchema(bool $migrate = false): array
    {
        return (new Schema\Compiler())->compile(
            new Schema\Registry($this->database),
            $this->getSchemaGenerators($migrate),
            (array)config('cycle.schema.defaults')
        );
    }

    private function getSchemaGenerators(bool $migrate = false): array
    {
        $generators = [
            new Schema\Generator\ResetTables(),
            new Annotated\Embeddings($this->classLocator),
            new Annotated\Entities($this->classLocator),
            new Annotated\MergeColumns(),
            new Schema\Generator\GenerateRelations(),
            new Schema\Generator\ValidateEntities(),
            new Schema\Generator\RenderTables(),
            new Schema\Generator\RenderRelations(),
            new Annotated\MergeIndexes(),
            new Schema\Generator\GenerateTypecast(),
        ];

        if ($migrate) {
            if ($this->isSyncMode()) {
                $generators[] = new Schema\Generator\SyncTables();
            } else {
                $generators[] = new GenerateMigrations(
                    $this->migrator->getRepository(),
                    $this->migrator->getConfig()
                );
            }
        }

        return $generators;
    }

    private function getSchema(): array
    {
        if (!config('cycle.schema.cache.enabled')) {
            return $this->compileSchema(true);
        }

        return $this->cache->rememberForever(
            self::SCHEMA_STORAGE_KEY,
            fn() => $this->compileSchema(true)
        );
    }
}
