<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm;

use Cycle\Annotated;
use Cycle\Database\DatabaseProviderInterface;
use Cycle\ORM\Schema as ORMSchema;
use Cycle\ORM\SchemaInterface;
use Cycle\Schema;
use Psr\SimpleCache\CacheInterface;
use Spiral\Tokenizer\ClassLocator;

final class SchemaManager
{
    private const SCHEMA_STORAGE_KEY = 'cycle.schema';

    public function __construct(
        private ClassLocator $classLocator,
        private DatabaseProviderInterface $database,
        private CacheInterface $cache
    ) {
    }

    public function isSyncMode(): bool
    {
        return (bool) config('cycle.schema.sync');
    }

    public function createSchema(): SchemaInterface
    {
        return new ORMSchema($this->getSchema());
    }

    public function flushSchemaData(): void
    {
        $this->cache->forget(self::SCHEMA_STORAGE_KEY);
    }

    public function compileSchema(array $generators): array
    {
        return (new Schema\Compiler())->compile(
            new Schema\Registry($this->database),
            $generators,
            (array) config('cycle.schema.defaults')
        );
    }

    public function getSchemaGenerators(bool $migrate = false): array
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

        if ($migrate && $this->isSyncMode()) {
            $generators[] = new Schema\Generator\SyncTables();
        }

        return $generators;
    }

    private function getSchema(): array
    {
        if (! config('cycle.schema.cache.enabled')) {
            return $this->compileSchema($this->getSchemaGenerators(true));
        }

        return $this->cache->rememberForever(
            self::SCHEMA_STORAGE_KEY,
            fn () => $this->compileSchema($this->getSchemaGenerators(true))
        );
    }
}
