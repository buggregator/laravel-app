<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Console\VariableCleaner;

class JobEventHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        $this->output->writeln(
            VariableCleaner::clean($payload['content']['job'], 0)
        );
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $this->output->writeln(sprintf(' <error> %s </error>  ', $payload['content']['event_name']));
        $this->output->newline();
    }
}
