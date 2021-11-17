<?php

declare(strict_types=1);

namespace Infrastructure\CycleOrm\Commands\Schema;

use Illuminate\Console\Command;
use Infrastructure\CycleOrm\SchemaManager;

final class RefreshCommand extends Command
{
    protected $signature = 'cycle:schema:refresh';
    protected $description = 'Refresh database schema';

    public function handle(SchemaManager $schemaManager): void
    {
        $this->call('db:wipe');

        $schemaManager->flushSchemaData();

        $this->info('Database schema cache flushed.');

        if ($schemaManager->isSyncMode()) {
            $schemaManager->createSchema();
        } else {
            $this->call('cycle:schema:migrate');
            $schemaManager->createSchema();
            $this->call('cycle:schema:migrate');
        }

        $this->info('Database schema updated.');
        $this->call('cycle:schema:render');
    }
}
