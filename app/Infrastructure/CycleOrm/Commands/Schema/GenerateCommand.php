<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Commands\Schema;

use Cycle\Migrations\Migrator;
use Cycle\Migrations\State;
use Cycle\Schema\Generator\Migrations\GenerateMigrations;
use Illuminate\Console\Command;
use Infrastructure\CycleOrm\SchemaManager;

final class GenerateCommand extends Command
{
    protected $signature = 'cycle:schema:generate';
    protected $description = 'Generate ORM schema migrations';

    public function handle(
        Migrator $migrator,
        SchemaManager $schemaManager
    ) {
        if (! $migrator->isConfigured()) {
            $migrator->configure();
        }

        foreach ($migrator->getMigrations() as $migration) {
            if ($migration->getState()->getStatus() !== State::STATUS_EXECUTED) {
                $this->error('Outstanding migrations found, run [migrate] first.');

                return;
            }
        }

        $generators = $schemaManager->getSchemaGenerators();
        $generators[] = new GenerateMigrations(
            $migrator->getRepository(), $migrator->getConfig()
        );

        $schemaManager->compileSchema($generators);
    }
}
