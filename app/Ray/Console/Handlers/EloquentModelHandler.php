<?php
declare(strict_types=1);

namespace App\Ray\Console\Handlers;

use App\Ray\Console\VariableCleaner;

class EloquentModelHandler extends AbstractHandler
{
    public function handle(array $payload): void
    {
        if (empty($payload['content'])) {
            $this->output->writeln('Empty data.');
            return;
        }

        $this->output->writeln(VariableCleaner::clean($payload['content']['attributes'], 0));
    }

    public function printTitle(array $payload): void
    {
        parent::printTitle($payload);

        $this->output->writeln(sprintf(' <error> %s </error>  ', $payload['content']['class_name']));
        $this->output->newline();
    }

    public function shouldBeSkipped(array $payload): bool
    {
        return empty($payload['content']);
    }
}
