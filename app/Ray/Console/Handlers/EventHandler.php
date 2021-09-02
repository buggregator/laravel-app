<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Console\VariableCleaner;

class EventHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if (isset($payload['content']['event'])) {
            $this->output->writeln(
                VariableCleaner::clean($payload['content']['event'], 0)
            );
        }

        if (isset($payload['content']['payload'])) {
            $this->output->writeln(
                VariableCleaner::clean($payload['content']['payload'], 0)
            );
        }
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $this->output->writeln(sprintf(' <error> %s </error>  ', $payload['content']['name']));
        $this->output->newline();
    }
}
