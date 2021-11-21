<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Commands\Schema;

use Cycle\Migrations\Migrator;
use Illuminate\Console\Command;

final class MigrateCommand extends Command
{
    protected $signature = 'cycle:schema:migrate';
    protected $description = 'Migrate ORM schema';

    public function handle(Migrator $migrator)
    {
        if (! $migrator->isConfigured()) {
            $migrator->configure();
        }

        while (($migration = $migrator->run()) !== null) {
            $this->info('Migrate '.$migration->getState()->getName());
        }
    }
}
