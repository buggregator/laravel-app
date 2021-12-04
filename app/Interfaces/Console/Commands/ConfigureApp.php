<?php

declare(strict_types=1);

namespace Interfaces\Console\Commands;

use Cycle\Database\DatabaseProviderInterface;
use Cycle\Database\Driver\SQLite\SQLiteDriver;
use Illuminate\Console\Command;

class ConfigureApp extends Command
{
    protected $signature = 'app:configure';

    public function handle(DatabaseProviderInterface $database)
    {
        $driver = $database->database()->getDriver();

        if (
            $driver instanceof SQLiteDriver
            //&& ! file_exists($database = database_path('database.sqlite'))
        ) {
            if (! file_exists($database = $driver->__debugInfo()['options']->connection->database)) {
                touch($database);
                chmod($database, 0777);
                $this->info(sprintf('SQLite database file [%s] is created.', $database));
            }
        }

        if (config('app.debug')) {
            $this->call('db:wipe');
            $this->call('cycle:schema:render');
        }

        $this->call('cycle:schema:migrate');

        $this->call('user:create');
    }
}
